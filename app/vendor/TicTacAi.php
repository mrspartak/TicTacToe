<?

class TicTacAi {
	
	public $ttt;
	public $possibleMoves;
	public $oppositeMoves;
	
	protected $types = array(1, -1, null);
	
	public function __construct($ttt) {
		$this->ttt = $ttt;
		
		$this->checkOppositeMoves();
		$this->checkPossibleMoves();
	}
	
	public function makeMove() {
		for($count = 4; $count > 0; $count--) {
			if(!empty($this->possibleMoves[$count])) {
				$moves = $this->possibleMoves[$count];
			}
			if(!empty($this->oppositeMoves[$count])) {
				$moves = $this->oppositeMoves[$count];
			}
			
			if($moves) {
				$length = count($moves)-1;
				$move = $moves[rand(0, $length)];
				break;
			}
		}
		
		return $move;
	}
	
	protected function checkPossibleMoves() {
		$var; $result;
		if($this->ttt->parsedColumns[-1])
			foreach($this->ttt->parsedColumns[-1] as $i => $move) {
				$count = $move['count'];
				$move['type'] = 'column';
				$var[$count][] = $move;
			}
		if($this->ttt->parsedRows[-1])
			foreach($this->ttt->parsedRows[-1] as $i => $move) {
				$count = $move['count'];
				$move['type'] = 'row';
				$var[$count][] = $move;
			}
		
		if($var)
			foreach($var as $k => $moves) {
				foreach($moves as $i => $move) {
					list($i1, $j1) = $move['start'];
					list($i2, $j2) = $move['end'];
					if($move['type'] == 'row') {
						--$j1; ++$j2;
					} else {
						--$i1; ++$i2;
					}
					if(!in_array($this->ttt->moves[$i1][$j1], $this->types)) $result[$k][] = array($i1, $j1);
					if(!in_array($this->ttt->moves[$i2][$j2], $this->types)) $result[$k][] = array($i2, $j2);
				}
			}
		
		$this->possibleMoves = $result;
	}
	
	protected function checkOppositeMoves() {
		$var; $result;
		if($this->ttt->parsedColumns[1])
			foreach($this->ttt->parsedColumns[1] as $i => $move) {
				$count = $move['count'];
				$move['type'] = 'column';
				$var[$count][] = $move;
			}
		if($this->ttt->parsedRows[1])
			foreach($this->ttt->parsedRows[1] as $i => $move) {
				$count = $move['count'];
				$move['type'] = 'row';
				$var[$count][] = $move;
			}

		if($var)
			foreach($var as $k => $moves) {
				foreach($moves as $i => $move) {
					list($i1, $j1) = $move['start'];
					list($i2, $j2) = $move['end'];
					if($move['type'] == 'row') {
						--$j1; ++$j2;
					} else {
						--$i1; ++$i2;
					}
					if(!in_array($this->ttt->moves[$i1][$j1], $this->types)) $result[$k][] = array($i1, $j1);
					if(!in_array($this->ttt->moves[$i2][$j2], $this->types)) $result[$k][] = array($i2, $j2);

				}
			}
		
		$this->oppositeMoves = $result;
	}
	
	
}