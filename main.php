<?php
const machineSpeeds = [
	'chocolateMaker' => 600,
	'icecreamMaker' => 1200,
	'muffinMaker' => 120
];

const productsCost = [
	'chocolate' => 90,
	'icecream' => 45,
	'muffin' => 27
];



class machine {
	public $id;
	public $speed;
	public $products = 0;
	
	function __construct($speed) {
		$this->id = rand();
		$this->speed = $speed;
	}
	
	function produce() {
		$this->products += $this->speed;
	}
	function harvest() {
		$harvest = $this->products;
		$this->products = 0;
		return $harvest;
	}
}



function createMachines($machinesToCreate, $machineSpeeds = machineSpeeds) {
	global $machines;
	
	foreach ($machinesToCreate as $machineType => $numberOfMachines) {
		if (!isset($machines[$machineType])) {
			$machines[$machineType][0] = NULL;
			$currentNumberOfMachines = 0;
		} else {
			$currentNumberOfMachines = count($machines[$machineType]);
		}
		
		for ($i = $currentNumberOfMachines; $i < ($currentNumberOfMachines + $numberOfMachines); $i++) {
			if ($machineType == 'icecreamMaker' && $i == 2) { // Вторая машинка для мороженного пускай будет сломанная
				$machines[$machineType][$i] = new machine(13);
			} else {
				$machines[$machineType][$i] = new machine($machineSpeeds[$machineType]);
			}
		}
	}
}

function produce($hours = 1) {
	global $machines;
	
	foreach ($machines as $machineType => $machinesOfTheType) {
		$machinesOfTheTypeCounter = count($machinesOfTheType);
		
		for ($i = 0; $i < $machinesOfTheTypeCounter; $i++) {
			for ($l = 0; $l < $hours; $l++) {
				$machinesOfTheType[$i]->produce();
			}
		}
	}
}

function harvest() {
	global $machines;
	global $harvest;
	
	foreach ($machines as $machineType => $machinesOfTheType) {
		$machinesOfTheTypeCounter = count($machinesOfTheType);
		
		if (!isset($harvest[$machineType])) {
			$harvest[$machineType] = 0;
		}
		for ($i = 0; $i < $machinesOfTheTypeCounter; $i++) {
			$harvest[$machineType] += $machinesOfTheType[$i]->harvest();
		}
	}
}

function tell($what, $productsCost = productsCost) {
	global $machines;
	global $harvest;
	
	$whatArray = explode(', ', $what);
	$whatArrayCounter = count($whatArray);
	
	for ($i = 0; $i < $whatArrayCounter; $i++) {
		if ($whatArray[$i] == 'machines') {
			echo 'There currently are: <br>';
			
			foreach ($machines as $machineType => $machinesOfTheType) {
				$machinesOfTheTypeCounter = count($machinesOfTheType);
				
				echo $machinesOfTheTypeCounter, ' ', $machineType, 's <br>';
			}
			echo 'operating on the factory. <br><br>';
		}
		if ($whatArray[$i] == 'harvest') {
			echo 'Products in store: <br>';
			var_dump($harvest);
		}
		if ($whatArray[$i] == 'money') {
			$totalProductsCost = 0;
			foreach ($harvest as $machineType => $products) {
				$productType = substr($machineType, 0, -5);
				$costOfProductsOfThisType = $productsCost[$productType]*$products;
				$totalProductsCost += $costOfProductsOfThisType;
				
				echo $products, ' of ', $productType, ' is currently in store, and can be sold for ', $costOfProductsOfThisType, ' rubles. <br>';
			}
			echo 'The entire stock can be sold for ', $totalProductsCost, ' rubles. <br>';
		}
	}
}



// --------------------------------------------------------------------------------------------------------------------------------------



$machinesToCreate = [
	'chocolateMaker' => 2,
	'icecreamMaker' => 3,
	'muffinMaker' => 1
];
createMachines($machinesToCreate);
tell('machines');

produce(12);
harvest();
tell('money');


$machinesToCreate = [
	'icecreamMaker' => 1,
	'muffinMaker' => 1
];
createMachines($machinesToCreate);
tell('machines');

produce(12);
harvest();
tell('money');
?>
