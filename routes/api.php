<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/ping', function (Request  $request) {

    $requestId = $request->hasHeader('request-id') ? $request->header('request-id') : (string) Str::uuid();
    Log::info(dump($request->header()) . '\n' . dump(Input::all()));
    Log::info(dump([
            "request_id" => $requestId,
            "status" => "success",
            "code" => "20000",
            "error_message" => "",
            "data" => [
                "message" => "Сервис работает",
            ]
        ]));

    return response([
        "request_id" => $requestId,
        "status" => "success",
        "code" => "20000",
        "error_message" => "",
        "data" => [
            "message" => "Сервис работает",
        ]
    ]);
});

Route::middleware('auth:api')->get('/user/info', function (Request $request) {
    /** @var \App\User */
    $user = Session::get('user');
    Log::info(dump($request->header()) . '\n' . dump(Input::all()));

    $requestId = $request->hasHeader('request-id')
        ? $request->header('request-id')
        : (string) Str::uuid();

    Log::info(dump([
        "request_id" => $requestId,
        "status" => "success",
        "code" => "20000",
        "error_message" => "",
        "data" => [
            "fio" => $user->last_name . " " . $user->name . " " . $user->patronymic,
            "bd" => (string) DateTime::createFromFormat("Y-m-d H:i:s", $user->birth_day),
            "date" => (string) DateTime::createFromFormat("Y-m-d H:i:s", $user->created_at),
            "is_active" => (string) !$user->blocked,
        ]
    ]));

    return response([
        "request_id" => $requestId,
        "status" => "success",
        "code" => "20000",
        "error_message" => "",
        "data" => [
            "fio" => $user->last_name . " " . $user->name . " " . $user->patronymic,
            "bd" => (string) DateTime::createFromFormat("Y-m-d H:i:s", $user->birth_day),
            "date" => (string) DateTime::createFromFormat("Y-m-d H:i:s", $user->created_at),
            "is_active" => (string) !$user->blocked,
        ]
    ]);
});

Route::middleware('auth:api')->post(' /user/create', function (Request $request) {
    $data = Input::all();

    Log::info(dump($request->header()) . '\n' . dump(Input::all()));

    $requestId = $request->hasHeader('request-id')
        ? $request->header('request-id')
        : (string) Str::uuid();

    $user = new User();

    $user->last_name = $data['last_name'];
    $user->name = $data['name'];
    $user->patronymic = $data['middle_name'];
    $user->birth_day = $data['bd'];
    $user->token = md5($user->last_name . " " . $user->name . " " . $user->patronymic . $data['bd']);

    $user->save();

    Log::info(dump([
        "request_id" => $requestId,
        "status" => "success",
        "code" => "20000",
        "error_message" => "",
        "data" => [
            "message" => " Пользователь успешно создан",
        ],
    ]));

    return response([
        "request_id" => $requestId,
        "status" => "success",
        "code" => "20000",
        "error_message" => "",
        "data" => [
            "message" => " Пользователь успешно создан",
        ],
    ]);
});

Route::any('*', function (Request $request) {

    Log::info(dump($request->header()) . '\n' . dump(Input::all()));

    $requestId = $request->hasHeader('request-id')
        ? $request->header('request-id')
        : (string) Str::uuid();

    Log::info(dump([
        "request_id" => $requestId,
        "status" => "error",
        "code" => "40001",
        "error_message" => "Вызываемый метод отсутствуе",
    ]));

    return response([
            "request_id" => $requestId,
            "status" => "error",
            "code" => "40001",
            "error_message" => "Вызываемый метод отсутствуе",
        ]
    );
});
