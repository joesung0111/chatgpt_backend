<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/messages",
     *     summary="取得使用者的歷史訊息",
     *     tags={"Message"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="成功回傳訊息列表",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Message"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $request->user()->messages()->get();
    }

    /**
     * @OA\Post(
     *     path="/api/message",
     *     summary="送出訊息給 ChatGPT 並儲存紀錄",
     *     tags={"Message"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="string", example="你好，今天幾號？")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="GPT 回應的訊息",
     *         @OA\JsonContent(
     *             @OA\Property(property="reply", type="string", example="今天是 4 月 9 日。")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="未授權"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = $request->user();

        Message::create([
            'user_id' => $user->id,
            'role' => 'user',
            'content' => $request->content,
        ]);

        $reply = '無法取得 GPT 回覆';

        if (env('GPT_FAKE_REPLY', false)) {
            // 假資料回覆
            $reply = '這是模擬 GPT 回覆：NT科技好棒棒。';
        } else {
            $openaiKey = env('OPENAI_API_KEY');

            $response = Http::withToken($openaiKey)
                ->timeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $request->content],
                    ],
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                $reply = '[OpenAI 錯誤] ' . $data['error']['message'];
            } else {
                $reply = $data['choices'][0]['message']['content'] ?? $reply;
            }
        }

        Message::create([
            'user_id' => $user->id,
            'role' => 'gpt',
            'content' => $reply,
        ]);

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
