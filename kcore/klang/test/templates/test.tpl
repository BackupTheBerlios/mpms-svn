<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title>{ki const="title"}Test klang{/ki}</title>
  <meta content="Boris Tomic" name="author" />
</head>
<body>
<h1>{ki const="page_title"}Test klangSmarty{/ki}</h1>
<form action="test.php" method="post">
{ki const="change_lang"}Change Language to{/ki}:&nbsp;
  <select name="lang">
  <option value="en">{ki const="en"}Engleski{/ki}</option>
  <option value="hr">{ki const="hr"}Hrvatski{/ki}</option>
  <option selected="selected" value="sel">{ki const="sel_lang"}select language{/ki}</option>
  </select>
  <input name="change" type="submit" value="Submit" />
</form>
<p>{ki const="desc"}This is test file for
klanfSmarty extension. This is small extension
which add multilanguage support to Smarty.{/ki}</p>
<p>{ki const="desc1"}Useage is same as Smarty. Only
few minor diferences are in
constructor regarding usage.{/ki}</p>
</body>
</html>
