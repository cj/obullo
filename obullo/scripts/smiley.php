<script type="text/javascript" charset="utf-8">
<!--
var smiley_map = <?php echo $m; ?>;

function insert_smiley(smiley, field_id)
{
    var el = document.getElementById(field_id), newStart;

    if ( ! el && smiley_map[field_id]) {
        el = document.getElementById(smiley_map[field_id]);
    
        if ( ! el)
            return false;
    }

    el.focus();
    smiley = " " + smiley;

    if ('selectionStart' in el) {
        newStart = el.selectionStart + smiley.length;

        el.value = el.value.substr(0, el.selectionStart) +
                        smiley +
                        el.value.substr(el.selectionEnd, el.value.length);
        el.setSelectionRange(newStart, newStart);
    }
    else if (document.selection) {
        document.selection.createRange().text = smiley;
    }
}
//-->
</script>