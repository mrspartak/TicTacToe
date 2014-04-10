<?
$app->get(
	'/',
	function () use ($app) {
		
		$records = Record::find(array(
    	"limit" => 2,
			"order" => 'id DESC'
		));
		echo $app['view']->render(
			'index/index', array(
				'records' => $records
			)
		);
	}
);

$app->post(
	'/ajax',
	function () use ($app) {
		if (!$app->request->isAjax()) {
			$app->response->setStatusCode(404, "Not Found")->sendHeaders();
			return false;
		}
		
		$action = $app->request->getPost('action');
		$data   = $app->request->getPost('data');
		$filter = new \Phalcon\Filter();

		switch ($action) {
			case 'check_state':
				$moves = $data['data'];
				$TTT = new TicTacToe($moves);
				
				$response = $TTT->checkState();
				$response = ($response === false) ? array('status' => 'fair') : $response;
			break;
			
			case 'ai_move':
				$moves = $data['data'];
				$TTT = new TicTacToe($moves);
				$ai = new TicTacAi($TTT);

				$response = $ai->makeMove();
			break;
			
			case 'save_record':
				$name = $data['name'];
				$time = $data['time'];
				
				$rec = new Record();
				$rec->name = $name;
				$rec->time = $time;
				$rec->save();
				
				$response = $data;
			break;
      
			default:
				$app->response->setStatusCode(404, "Not Found")->sendHeaders();
		}

		$app
			->response
			->setContentType('application/json', 'UTF-8')
			->setJsonContent($response, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE)
			->send();
    }
);


$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(500, "Error")->sendHeaders();
        echo $app['view']->render('errors/404', array('message' => 'error_404'));
    }
);