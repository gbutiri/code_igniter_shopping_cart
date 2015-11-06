<?php 
function rmspace($buffer){ 
	return preg_replace('~>\s*\n\s*<~', '><', $buffer); 
}
