
</div> <!-- id="responsive" -->
<footer>© Xontik 2016</footer>
</body>
<?php
  foreach ($jss as $js) {
  	echo "<script type='text/javascript' src='".js_url($js)."'></script>\n";
  } ?>
</html>