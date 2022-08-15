<?php

/**
 * There are N block from 0 to N-1. A couple of frogs were sitting together on one block. They had a quarrel and need to jump away from one another. 
 * The frogs can only jump to another block if the height of the other block is greater than equal to the current one. You need to find the longest 
 * possible distance that they can possible create between each other, if they also choose to sit on an optimal starting block initially.
 */

$tests = [
	2 => [1,1],
	6 => [6,5,1,2,2,3,2,1,2,3,2,1],
	3 => [2,6,8,5],
	4 => [1,5,5,2,6],
	5 => [1,5,5,2,5,5,1],
	8 => [5,1,9,10,2,4,3,11,10,9,8,12,14,15,17,3,2,7,8,1,2,4,8,9,7],
	15 => [1,1,1,2,2,2,1,1,1,2,3,4,5,5,5,5,6,6,5,4,3,3,3,2,1,1,2,3,4,5,6,1]
];

$success = 0;
$errors = [];
foreach ($tests as $ans=>$blocks) {
	$result = distCalc($blocks) ?: "none";
	if ($result !== $ans) {
		$errors[] = "Got $result expected $ans";
	} else {
		$success++;
	}
}

$testsNum = count($tests);
if ($errors) {
	var_dump("ERRORS: ", $errors);

}
echo "{$success}/{$testsNum} passed.";

/**
 * Calculates the max travel distance between blocks,
 * where only blocks of even or greater height can be
 * stepped to, and one can go left or right from any
 * point in the array of blocks.
 *
 * @param array $blocks
 * @return integer
 */
function distCalc(array $blocks): int {
	// Handles the simple case when there are only 1 or 2 stones
	if (count($blocks) <= 2) {
		return count($blocks);
	}
	$counter = 0;
	$back_counter = 0;
	// Max dist will be a minimum of 2,
	// when there are 3 or more blocks
	$max_dist = 2;
	for ($i=0; $i < count($blocks); $i++) {
		$prev = isset($blocks[$i-1]) ? $blocks[$i-1] : null;
		$current = $blocks[$i];
		$next = isset($blocks[$i+1]) ? $blocks[$i+1] : null;
		$counter++;
		// Handles end of the current run, 
		// because next block can't be jumped to
		if ($prev <= $current && $current > $next) {
			$total = $counter + $back_counter;
			$max_dist = $total > $max_dist ? $total : $max_dist;
			// If the there were more than one blocks at the peak/plateau,
			// we need to calculate how many by counting backwards to where
			// the peak began.
			if ($prev === $current) {
				$back_counter = getBackCount($i, $blocks);
			// If there was only one block at the peak,
			// reset the back counter to 0
			} else {
				$back_counter = 0;
			}
			// Reset the counter to 1 to account
			// for the current block
			$counter = 1;
		// End of the array
		} else if (!$next) {
			$total = $counter + $back_counter;
			$max_dist = $total > $max_dist ? $total : $max_dist;
		}
	}
	return $max_dist;
}

/**
 * Counts backward until the previous number is lower
 *
 * @param integer $i
 * @param array $blocks
 * @return integer
 */
function getBackCount(int $i, array $blocks): int {
	$counter = 0;
	while ($blocks[$i] === $blocks[$i-1]) {
		$counter++;
		$i--;
	}
	return $counter;
}