<?php
namespace Gwdhost\MailTesting\Http\Controllers;

use App\Http\Controllers\Controller;
use Laravel\Nova\Fields\BelongsTo;
use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Mail;

class MailsController extends Controller
{
    public function mails(NovaRequest $request)
    {
        $items = $this->get_mails($request);

        return response()->json([
            'data' =>  $items
        ]);
    }

    public function send_email(NovaRequest $request)
    {
        $data = $request->validate([
            'mail_id' => 'required|string',
            'email' => 'required|email',
            'args' => 'required|array'
        ]);

        $items = $this->get_mails($request);

        $item = $items->where('id', $data['mail_id'])->first();

        if($item){
            $args = array_map(function($arg, $index) use ($data){
                if (isset($arg['model'])) {
                    return $arg['model']::find($data['args'][$index]);
                }

                return $data['args'][$index] ?? null;
            }, $item['args'], array_keys($item['args']));

            $mail = $item['class'];

            Mail::to($data['email'])->send(new $mail(...$args));

            return response()->json([
                'status' => true,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Mail doesnt exist.'
        ]);
    }

    public function view_email(NovaRequest $request)
    {
        $data = $request->validate([
            'mail_id' => 'required|string',
            'args' => 'required|array'
        ]);

        $items = $this->get_mails($request);

        $item = $items->where('id', $data['mail_id'])->first();

        if($item){
            $args = array_map(function($arg, $index) use ($data){
                if (isset($arg['model'])) {
                    return $arg['model']::find($data['args'][$index]);
                }

                return $data['args'][$index] ?? null;
            }, $item['args'], array_keys($item['args']));

            $mail = $item['class'];

            return (new $mail(...$args))->render();
        }

        return response()->json([
            'status' => false,
            'message' => 'Mail doesnt exist.'
        ]);
    }

    private function get_mails(NovaRequest $nova_request)
    {
        $mails = config('mail-testing.mails', []);
        $items = collect();


        foreach ($mails as $mail) {
            $items->push([
                'id' => $mail['class'],
                'name' => $mail['label'] ?? $mail['class'],
                'description' => $mail['description'] ?? null,
                'class' => $mail['class'],
                'args' => array_map(function($arg, $index) use ($nova_request){

                    if (is_array($arg)) {
                        return array_merge([
                            'id' => '' . $index,
                            'type' => 'text',
                        ], $arg);
                    }

                    $instance = new $arg($arg::$model);
                    $fields = null;

                    return [
                        'id' => $arg,
                        'nova' => $arg,
                        'model' => $arg::$model,
                        'label' => $arg::label(),
                        'items' => $instance::buildIndexQuery(
                            $nova_request, $instance::newModel()->newQuery(),
                        )->get(),
                        'title' => $arg::$title,
                    ];
                }, $mail['args'], array_keys($mail['args'])),
            ]);
        }

        return $items;
    }
}
