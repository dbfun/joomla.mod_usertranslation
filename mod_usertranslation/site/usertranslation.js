function googleSectionalElementInit() {
  new google.translate.SectionalElement({
    sectionalNodeClassName: 'goog-trans-section',
    controlNodeClassName: 'goog-trans-control',
    background: '#fea575'
  }, 'google_sectional_element');
}

function strip_tags( str ){	// Strip HTML and PHP tags from a string
	return str.replace(/<\/?[^>]+>/gi, '');
}

function enable_submit()
{
$('#save_translation').attr('disabled', false);
}

function ajax_save_translate(primary_key, translation)
{
	$.getJSON('/modules/mod_usertranslation/ajax.php',{"primary_key":primary_key, "translation":translation, "ajax":"ajax"}, function(json){ // 
	$('#ajax_translate').html(json.nt);
	$('#s_response').html(json.th);
	});
}