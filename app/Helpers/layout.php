<?php
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

if(!function_exists("set_layout_arg")){
	function set_layout_arg ($name, $value = null) {
		$args = [];
		if(!is_array($name)) $name = [$name];
		if(!is_array($value)) $value = [$value];
		foreach($name as $k => $v){
			$key = (!is_int($k)) ? $k : $v;
			$val = (!is_int($k)) ? $v : $value[$k];

			Config::set("layout.args.{$key}", $val);
		}
	}
}

if(!function_exists("layout_view")){
	function layout_view ($viewPath, $args = [], $templatePath = null) {
		$globalArgs = Config::get("layout.args");
		if(!$templatePath) $templatePath = env("TEMPLATE_PATH");

		$uri = Route::getCurrentRoute()->uri;
		$parameters = Route::getCurrentRoute()->parameters;

		foreach($parameters as $key => $val){
			$uri = str_replace("{{$key}}", $val, $uri);
		}

		$layoutArgs = [
			"viewPath" => $viewPath,
			"args" => $args,
			"currentPath" => "/$uri",
			"user" => session()->get("user")
		];
		$layoutArgs = array_merge($layoutArgs, $globalArgs);
		return view($templatePath, $layoutArgs);
	}
}

if(!function_exists("to_object")){
	function to_object ($arr){
		return json_decode(json_encode($arr));
	}
}

if(!function_exists("curlSendRequest")){
	function curlSendRequest ($method, $endpoint, $data = [], $headers = [], $timeout = null, $encode = false) {
		if(!$timeout) $timeout = env("WS_TIMEOUT");
		$req = curl_init();

		if($encode && in_array(gettype($data), ["object", "array"])) $data = json_encode($data);
		curl_setopt($req, CURLOPT_URL, $endpoint);
		curl_setopt($req, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($req, CURLOPT_POSTFIELDS, $data);
		curl_setopt($req, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($req, CURLOPT_TIMEOUT, $timeout * 1000);
		curl_setopt($req, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($req, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($req);
		if($encode && in_array(gettype($response), ["object", "array"])) $response = json_encode($response);
		curl_close($req);
		return json_decode($response);
	}
}

if(!function_exists("build_post_fields")){
	function build_post_fields( $data,$existingKeys='',&$returnArray=[]){
	    if(($data instanceof CURLFile) or !(is_array($data) or is_object($data))){
	        $returnArray[$existingKeys]=$data;
	        return $returnArray;
	    }
	    else{
	        foreach ($data as $key => $item) {
	            build_post_fields($item,$existingKeys?$existingKeys."[$key]":$key,$returnArray);
	        }
	        return $returnArray;
	    }
	}
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifests = [];
        if (! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }
        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            return new HtmlString("//localhost:8080{$path}");
        }
        $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');
        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }
        $manifest = $manifests[$manifestPath];
        if (! isset($manifest[$path])) {
            report(new Exception("Unable to locate Mix file: {$path}."));
            if (! app('config')->get('app.debug')) {
                return $path;
            }
        }
        return new HtmlString($manifestDirectory.$manifest[$path]);
    }
}