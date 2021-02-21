<html>

<head>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>This is header 1</title>
</head>

<body>

<p> You can try printing this page. Three pages will be produced, using the two
headers below as dividers.</p>
<h2>This is header 1</h2>
<p>With CSS (level 2), the grip webmasters have over their
      webpages extends to the printer. To satisfy the controlling tendencies
      inside most of us, IE5 and NS6 give you the power to insert artificial page breaks
      for printing. Now your site can look the way you want it to even after the
computer's turned off!</p>
<h2>This is header 2</h2>
<p>With CSS (level 2), the grip webmasters have over their
      webpages extends to the printer. To satisfy the controlling tendencies
      inside most of us, IE5 and NS6 give you the power to insert artificial page breaks
      for printing. Now your site can look the way you want it to even after the
computer's turned off!</p>
<hr size="1">
<p><!--webbot bot="HTMLMarkup" startspan -->


<form name="myform">
<input type="checkbox" name="mybox" onClick="breakeveryheader()" checked>Print each header on seperate page?
</form>


<script>
function breakeveryheader(){
if (!document.getElementById){
alert("You need IE5 or NS6 to run this example")
return
}
var thestyle=(document.forms.myform.mybox.checked)? "always" : "auto"
for (i=0; i<document.getElementsByTagName("H2").length; i++)
document.getElementsByTagName("H2")[i].style.pageBreakBefore=thestyle
}

</script><!--webbot bot="HTMLMarkup" endspan -->
</p>

</body>

</html>