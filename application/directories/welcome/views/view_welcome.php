
<?php this()->head .= js('js/welcome.js'); ?>

<!-- body tag content -->

<h1>Welcome to Obullo Framework !</h1> 

<p><?php echo $var;?></p><br />

<p></p>

<p>If you would like to edit this page you'll find <b>View</b> located at:</p>
<code>application/directories/welcome/views/view_welcome.php</code>

<p>The corresponding <b>Controller</b> for this page is found at:</p>
<code>application/directories/welcome/controllers/start.php</code>

<p>The corresponding <b>Global controller</b> for this page is found at:</p>
<code>application/parents/Global_controller.php</code>

<code>
<input type="button" onclick="test_me();" value="Script Test !"/>
</code>

<p>The corresponding <b>Script</b> file for this page is found at:</p>
<code>application/directories/welcome/scripts/welcome.php</code>

<p>The corresponding <b>Global View</b> file for this page is found at:</p>
<code>application/views/view_base_layout.php</code>

<p><b>Note:</b> If you are new to Obullo Framework, you should start by 
reading the <a href="http://obullo.com/user_guide/index.html">User Guide</a>.</p>

<p><br />Page rendered in {elapsed_time} seconds <?php if(function_exists('memory_get_usage')) {?> using {memory_usage} of memory <?php } ?> (Used Head_tag helper, Script file and Global Controller).</p>

<!-- body tag content -->