<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', -1],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
//        if (!$event->isMainRequest()) {
//            // don't do anything if it's not the main request
//            return;
//        }
//
//        if ($event->getRequest()->isMethod(Request::METHOD_PATCH)) {
//            $content = $this->parse_raw_http_request($event->getRequest()->getContent());
//
//            $event->getRequest()->files->replace($content['file']);
//            /** @var UploadedFile $file */
//            $file = $event->getRequest()->files->get('imageFile');
//
//            unset($content['file']);
//
//            $event->getRequest()->request->replace($content);
//        }

        //                dd($event->getRequest()->files);
    }

    public function parse_raw_http_request(string $content): array
    {
        $data = [];

        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $content);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visibile char

            // parse uploaded files
            if (false !== strpos($block, 'application/octet-stream')) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
                $data['files'][$matches[1]] = $matches[2];
            } else {
                // parse all other fields
                if (false !== strpos($block, 'filename')) {
                    // match "name" and optional value in between newline sequences
                    preg_match('/name=\"([^\"]*)\"; filename=\"([^\"]*)\"[\n|\r]+([^\n\r].*)$/s', $block, $matches);
                    preg_match('/Content-Type: (.*)?/', $matches[3], $mime);
                    $mime[1] = preg_replace('/\s+/', '', $mime[1]);

                    // match the mime type supplied from the browser
                    $image = preg_replace('/Content-Type: (.*)[^\n\r]/', '', $matches[3]);
                    $image = preg_replace('/\s+/', '', $image);

                    // get current system path and create tempory file name & path
                    $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
                    $path = $tmp_dir.'/php'.substr(sha1((string) rand()), 0, 6);

                    // write temporary file to emulate $_FILES super global
                    $err = file_put_contents($path, $image);
                    chmod($path, 0100600);

                    // Did the user use the infamous &lt;input name="array[]" for multiple file uploads?
                    if (preg_match('/^(.*)\[\]$/i', $matches[1], $tmp)) {
                        $data[$tmp[1]]['name'][] = $matches[2];
                        $data[$tmp[1]]['full_path'][] = $matches[2];
                        // Create the remainder of the $_FILES super global
                        $data[$tmp[1]]['type'][] = $mime[1];
                        $data[$tmp[1]]['tmp_name'][] = $path;
                        $data[$tmp[1]]['error'][] = (false === $err) ? $err : 0;
                        $data[$tmp[1]]['size'][] = filesize($path);

                        $_FILES[$tmp[1]] = $data[$tmp[1]];
                    } else {
                        $data['file'][$matches[1]]['name'] = $matches[2];
                        $data['file'][$matches[1]]['full_path'] = $matches[2];
                        $data['file'][$matches[1]]['type'] = $mime[1];
                        $data['file'][$matches[1]]['tmp_name'] = $path;
                        $data['file'][$matches[1]]['error'] = (false === $err) ? $err : 0;
                        $data['file'][$matches[1]]['size'] = filesize($path);

                        $_FILES[$matches[1]] = $data['file'][$matches[1]];
                    }
                } else {
                    // match "name" and optional value in between newline sequences
                    preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);

                    if (preg_match('/^(.*)\[\]$/i', $matches[1], $tmp)) {
                        $data[$tmp[1]][] = $matches[2];
                    } else {
                        $data[$matches[1]] = $matches[2];
                    }
                    $data[$matches[1]] = $matches[2];
                }
            }
        }

        return $data;
    }
}
