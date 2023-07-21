<?php

define("PLUS", "+");
define("MINUS", "-");
define("MULTIPLY", "*");
define("DIVIDE", "/");
define("EXPONENT", "^");
define("UNKNOWN", "UNKNOWN");

define("OPERATOR", "OPERATOR");
define("CONSTANT", "CONSTANT");
define("VARIABLE", "VARIABLE");

define("VARIABLE_QUERY", "INSERT INTO `tblvariables` (`rule_id`, `name`, `value_type`, `description`) VALUES ('%s', '%s', '%s', '%s');");
define("RULE_QUERY", "INSERT INTO `tblrules` (`rule_id`, `teacher_id`, `subject_id`, `year`, `semester`, `category`, `rule`, `description`, `department`, `section`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');");
define("LIST_VARIABLES", "SELECT * FROM `tblvariables` WHERE `rule_id` = '%s';");
define("RULE_OF", "SELECT `rule` FROM `tblrules` WHERE `rule_id` = '%s';");
define("LIST_RULES", "SELECT * FROM `tblrules` where %s;");
define("VARIABLE_DELETE_QUERY", "DELETE FROM `tblvariables` WHERE `rule_id` = '%s';");
define("UPDATE_RULE_QUERY", "UPDATE `tblrules` SET `rule` = '%s' WHERE `tblrules`.`rule_id` = '%s' LIMIT 1;");
