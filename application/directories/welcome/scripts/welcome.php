
<script type="text/javascript" language="Javascript">

    // We load inline javascript files as views
    // This is the content::script(); or $this->content->script(); test function
    // we will build it in <head> tags
    
    function test_me()
    {
        alert('Hello World ! This is the my site base url <?php echo this()->base; ?> ');
        return false;
    }
    

</script>