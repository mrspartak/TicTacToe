<?

class TicTacToe {
	
	public $moves;
	public $parsedRows;
	public $parsedColumns;
	
	protected $types = array(1, -1);
	
	public function __construct($moves) {
		$this->moves = $moves;
		
		$this->parseRows();
		$this->parseColumns();
		//$this->parseCross();
	}
	
	protected function parseRows() {
		$turnType = 2; $dump; $result;
		for($i=0, $rows = count($this->moves); $i<= $rows; $i++) {
			$row = $this->moves[$i];
			
			for($j=0, $columns = count($row); $j <= $columns; $j++) {
				$cell = $row[$j];
				
				if($cell == $turnType) {
					$dump['count']++;
					$dump['end'] = array($i, $j);
				} else {
					if(in_array($turnType, $this->types))		
						$result[$turnType][] = $dump;
					$this->initDump($dump, $i, $j);
					$turnType = $cell;
				}
			}
		}
		
		$this->parsedRows = $result;
	}
	
	protected function parseColumns() {
		$turnType = 2; $dump; $result;
		for($i=0, $rows = count($this->moves); $i<$rows; $i++) {
			$row = $this->moves[$i];
			for($j=0, $columns = count($row); $j < $columns; $j++) {
				$tmpArray[$j][$i] = $this->moves[$i][$j];
			}
		}
		
		for($j=0, $columns = count($tmpArray); $j < $columns; $j++) {
			$column = $tmpArray[$j];
			
			for($i=0, $rows = count($column); $i < $rows; $i++) {
				$cell = $column[$i];
				
				if($cell == $turnType) {
					$dump['count']++;
					$dump['end'] = array($i, $j);
				} else {
					if(in_array($turnType, $this->types))		
						$result[$turnType][] = $dump;
					$this->initDump($dump, $i, $j);
					$turnType = $cell;
				}
			}
		}
	
		$this->parsedColumns = $result;
	}
	
	public function checkState() {
		$result = false;
		foreach($this->parsedColumns as $type => $moves) {
			foreach($moves as $i => $move) {
				if($move['count'] == 5) {
					$result = $move;
					$result['type'] = $type;
					
					break;
				}
			}
		}
		
		foreach($this->parsedRows as $type => $moves) {
			foreach($moves as $i => $move) {
				if($move['count'] == 5) {
					$result = $move;
					$result['type'] = $type;
					
					break;
				}
			}
		}
		
		return $result;
	}
	
	private function initDump(&$dump, $i, $j) {
		$dump = array();
		$dump['count'] = 1;
		$dump['start'] = array($i, $j);
		$dump['end'] = array($i, $j);
	}
}