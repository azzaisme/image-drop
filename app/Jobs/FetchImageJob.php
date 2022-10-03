<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\User;
use App\Notifications\ImageFailNotification;
use App\Notifications\ImageSuccessNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FetchImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public string $path,
        public User   $user
    ) {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Fetch from url

        $ch = curl_init();
        $options =  array(

            CURLOPT_URL => $this->path,
            CURLOPT_ENCODING => "",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPGET => true,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT => 60

        );

        curl_setopt_array($ch, $options);

        $contents = curl_exec($ch);

        // Check for errors and display error message
        if (curl_errno($ch)) {
            $this->throwError("Not a valid URL");
            return;
        }
        curl_close($ch);

        $name = substr($this->path, strrpos($this->path, '/') + 1);
        $name = explode("?", $name)[0];

        if (!Storage::disk('public')->put($name, $contents)) {
            $this->throwError("Image could not be created");
            return;
        }

        $mime = Storage::disk('public')->mimeType($name);

        if (strpos($mime, "image") < 0) {
            $this->throwError("Not an image");
            Storage::delete($name);
            return;
        }

        $image = new Image();
        $image->path = $this->path;
        $image->name = $name;
        $image->user_id = $this->user->id;
        $image->save();


        $this->user->notify(new ImageSuccessNotification($this->path));

    }

    private function throwError(string $error)
    {
        $this->user->notify(new ImageFailNotification($this->path, $error));
    }
}
