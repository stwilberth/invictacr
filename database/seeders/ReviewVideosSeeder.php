<?php

namespace Database\Seeders;

use App\Models\ReviewVideo;
use Illuminate\Database\Seeder;

class ReviewVideosSeeder extends Seeder
{
    public function run(): void
    {
        $videos = [
            ['stream_uid' => 'd4706b409ea647743ec9dffe96f9503f'],
            ['stream_uid' => '4320502d8b65b23e44ca8b8860a6c4d5'],
            ['stream_uid' => '1b164d924ff877e04eabf3ff350f4863'],
            ['stream_uid' => '06e9614540af48daa4d1ef5e47d17490'],
            ['stream_uid' => '63a7acc4e00b2d5de8e8ebdd57dfd107'],
            ['stream_uid' => '7be4a398961006e5b739b3c5c9347585'],
            ['stream_uid' => '0e2de703b549ffd0a92446bad6708dff'],
            ['stream_uid' => '87c4be1598d31afea67f8db764ef4333'],
            ['stream_uid' => 'ac90c6f10848a7b50d7fc9e1100c4c8a'],
            ['stream_uid' => 'c7ca6438b0601a62566602b18d0376be'],
            ['stream_uid' => '439fee2f0ae352fa2b31fe4cc7bd6bb7'],
            ['stream_uid' => '655c426e46289c58cacfef0fa95791e2'],
            ['stream_uid' => '7b5b1a983980f50f7ac626c61b457b3b'],
            ['stream_uid' => '71f183de26e242d24f6c9bd9cc1a6e5e'],
            ['stream_uid' => 'e297da37609e817be1fabb3321b5d13c'],
        ];

        foreach ($videos as $i => $video) {
            ReviewVideo::updateOrCreate(
                ['stream_uid' => $video['stream_uid']],
                ['orden' => $i + 1],
            );
        }
    }
}
