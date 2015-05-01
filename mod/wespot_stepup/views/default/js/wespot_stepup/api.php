<?php ?>
//<script>

function post_to_stepup(url, verb, context, value) {
    var body = {
        'verb': verb,
        'object': url,
        'context': context
    }

    if(value) {
        body['originalrequest'] = { 'value': value }
    }

    $.post('<?php echo $CONFIG->site->url?>/services/api/rest/json/?method=stepup.proxy', body)
}

//HOW TO USE:
//elgg_load_js('wespot_stepup');
//post_to_stepup("url", "verb", "context", { a: 'moo', b: 'boo'})
