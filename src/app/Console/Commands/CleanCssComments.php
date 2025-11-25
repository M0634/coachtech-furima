<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanCssComments extends Command
{
    protected $signature = 'css:clean {file?}';
    protected $description = 'CSSのコメントを削除（区切り用や短いコメント）';

    public function handle()
    {
        $file = $this->argument('file');
        $files = [];

        if ($file) {
            if (!File::exists($file)) {
                $this->error("ファイルが存在しません: {$file}");
                return 1;
            }
            $files[] = $file;
        } else {
            $files = File::allFiles(public_path('css'));
        }

        foreach ($files as $f) {
            $path = is_string($f) ? $f : $f->getRealPath();
            $css = File::get($path);

            // コメント削除
            $css = preg_replace([
                '/\/\*\s*-{3,}\s*.*?\s*-{3,}\s*\*\//s',
                '/\/\*[^*]{0,50}\*\//'
            ], '', $css);

            File::put($path, $css);
            $this->info("処理済み: {$path}");
        }

        $this->info("完了しました！");
        return 0;
    }
}
