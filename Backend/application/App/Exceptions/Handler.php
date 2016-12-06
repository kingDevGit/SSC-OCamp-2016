<?php namespace App\Exceptions;

use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {
	public function shouldReport(\Exception $e) {
		return false;
	}

	public function report(\Exception $e) {
		return;
	}

	public function render($request, \Exception $e) {
		$namespaces = explode('\\', get_class($e));
		$exception_name = $namespaces[count($namespaces) - 1];

		return response()->json([
			'success' => false,
			'exception' => $exception_name,
			'message' => $e->getMessage()
		], 400);
	}
}
