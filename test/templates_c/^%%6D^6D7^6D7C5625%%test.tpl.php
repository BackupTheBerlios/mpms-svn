<?php /* Smarty version 2.6.7, created on 2005-11-26 22:52:19
         compiled from test.tpl */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title>Test klang</title>
  <meta content="Boris Tomic" name="author" />
</head>
<body>
<h1>Test klangSmarty</h1>
<form action="test.php" method="post">
Change Language to:&nbsp;
  <select name="lang">
  <option value="en">Engleski</option>
  <option value="hr">Hrvatski</option>
  <option selected="selected" value="sel">select language</option>
  </select>
  <input name="change" type="submit" value="Submit" />
</form>
<p>This is test file for
klanfSmarty extension. This is small extension
which add multilanguage support to Smarty.</p>
<p>Useage is same as Smarty. Only
few minor diferences are in
constructor regarding usage.</p>
<h1>Dyn</h1>
<p><?php echo $this->_tpl_vars['tdyn']; ?>
</p>
</body>
</html>