<?php

namespace TeraBlaze\Libraries;

/**
 * Class Form
 * @package TeraBlaze\Libraries
 *
 * for form creation and operations
 */
class Form
{
	protected $err_flds;
	protected $vals;

	function start($vals = null, $action = null, $method = "POST")
	{
		$this->err_flds = array();
		$this->vals = $vals;
		if (is_null($action))
			$action = $_SERVER['PHP_SELF'];
		echo "<form action=\"$action\" method=\"$method\" accept-charset=UTF-8>";
		if (isset($_SESSION['csrftoken']))
			$this->hidden('csrftoken', $_SESSION['csrftoken']);
	}

	function end()
	{
		echo "</form>";
	}

	function title($title)
	{
		echo "<h1>$title</h1>";
	}

	function hidden($fld, $v)
	{
		$v = htmlspecial($v);
		echo "<input id=$fld type=hidden name=$fld value='$v'>";
	}

	function errors($err_flds)
	{
		$this->err_flds = $err_flds;
	}

	function text($fld, $label = null, $len = 50,
	              $placeholder = '', $break = true, $password = false,
	              $readonly = false)
	{
		if ($password)
			$type = 'password';
		else
			$type = 'text';
		if ($readonly)
			$ro = 'readonly';
		else
			$ro = '';
		$this->label($fld, $label, $break);
		$v = isset($this->vals[$fld]) ?
			htmlspecial($this->vals[$fld]) : '';
		echo "<input id=$fld type=$type size=$len name=$fld
      value='$v' placeholder='$placeholder' $ro>";
	}

	function textarea($fld, $label = null, $cols = 100,
	                  $rows = 5, $readonly = false)
	{
		$this->label($fld, $label, true);
		$v = isset($this->vals[$fld]) ?
			htmlspecial($this->vals[$fld]) : '';
		echo "<br><textarea id=$fld name=$fld cols=$cols
      rows=$rows>$v</textarea>";
	}

	function label($fld, $label, $break)
	{
		if (is_null($label))
			$label = $fld;
		if ($break)
			echo '<p class=label>';
		else
			$this->hspace();
		$st = isset($this->err_flds[$fld]) ?
			'style="color:red;"' : '';
		echo "<label class=label for=$fld $st>$label</label>";
	}

	function hspace($ems = 1)
	{
		echo "<span style='margin-left:{$ems}em;'></span>";
	}

	function foreign_key($fldfk, $fldvis, $label = null, $len = 50)
	{
		$vfk = isset($this->vals[$fldfk]) ? $this->vals[$fldfk] : '';
		$this->hidden($fldfk, $vfk);
		$fld = "{$fldfk}_label";
		$this->label($fld, $label, true);
		$v = isset($this->vals[$fldvis]) ?
			htmlspecial($this->vals[$fldvis]) : '';
		echo "<input id=$fld type=text size=$len name=$fld
      value='$v' readonly>";
		echo "<button class=button type=button
      onclick='ChooseSpecialty(\"$fldfk\");'>
      Choose...</button>";
		echo "<button class=button type=button
      onclick='ClearField(\"$fldfk\");'>
      Clear</button>";
	}

	function button($fld, $label = null, $break = true)
	{
		if ($break)
			echo '<p class=label>';
		echo "<input id=$fld class=button type=submit name=$fld
      value='$label'>";
	}

	function radio($fld, $label, $value, $break = true)
	{
		if ($break)
			echo '<p class=label>';
		$st = isset($this->err_flds[$fld]) &&
		$this->err_flds[$fld] == $value ?
			'style="color:red;"' : '';
		$checked = isset($this->vals[$fld]) &&
		$this->vals[$fld] == $value ? 'checked' : '';
		echo <<<EOT
    <input type=radio name=$fld value='$value' $checked>
    <label class=label for=$fld $st>$label</label>
EOT;
	}

	function menu($fld, $label, $values, $break = true,
	              $default = null)
	{
		$this->label($fld, $label, $break);
		echo "<select id=$fld name=$fld>";
		echo "<option value=''></option>";
		if (isset($this->vals[$fld]))
			$curval = $this->vals[$fld];
		else
			$curval = $default;
		foreach ($values as $k => $v) {
			if (is_numeric($k))
				$k = $v;
			echo "<option value='$v' " .
				($curval == $v ? "selected" : "") . ">$k</option>";
		}
		echo "</select>";
	}

	function checkbox($fld, $label, $break = true)
	{
		$this->label($fld, $label, $break);
		$checked = (empty($this->vals[$fld]) ||
			$this->vals[$fld] === '0') ? '' : 'checked';
		echo "<input id=$fld type=checkbox name=$fld
      value=1 $checked>";
	}

	function date($fld, $label, $break = true)
	{
		$this->text($fld, $label, 10, 'YYYY-MM-DD', $break);
		echo <<<EOT
    <script>
        $(document).ready(function() {
            $('#$fld').datepicker({dateFormat: 'yy-mm-dd'});
        });
    </script>
EOT;
	}

	function password_strength($fld, $userid)
	{
		echo '<span id=password-strength></span>';
		echo <<<EOT
    <script>
    $('#$fld').bind('keydown', function() {
        PasswordDidChange('$fld', '$userid');
    });
    </script>
EOT;
	}

}

?>