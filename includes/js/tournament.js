/*
 *All javascript treatmen on the participant manager page
 **/
jQuery(function() {
   jQuery("#participantsList").sortable();
});

/*
 *Create a row in the classement box
 **/
function _createRow(id, name)
{
    if(name==''){return false}

    var li = new jQuery('<li>',{
        'class': 'participant',
        id: 'participant_'+ id
    });

    li.append(name+'<br/>')
    var el = new jQuery('<input>',{
        'class': 'delete',
        type: 'button',
        value: 'X'

    });
    el.click(function(){deleteRow(this)});
    li.append(el);

    el = new jQuery('<input>',{
        type: 'hidden',
        value: id,
        name: 'coachids[]'
    });
    li.append(el);

    el = new jQuery('<input>',{
        type: 'hidden',
        value: name,
        name: 'coachnames[]'
    });
    li.append(el);

    el = jQuery('#roster_coaches').clone();
    el.attr('id','');
    el.attr('name','rosters[]');
    el.val(jQuery('#roster_coaches').val());
    li.append(el);

    jQuery('#participantsList').append(li);


}

/*
 *pulsate function for element highlight
 **/
function _pulsate( $element)
{
    $element.css('background','#970808')
        .animate({opacity: 0.2}, 300, 'linear')
        .animate({opacity: 1}, 200, 'linear')
        .animate({opacity: 0.2}, 300, 'linear')
        .animate({opacity: 1}, 200, 'linear',
            function(){
                $element.css('background','#FEB30A');
            }
        );
}

/*
 * calculate levenshtein between 2
 **/
function _levenshtein(s1, s2) {
    // http://kevin.vanzonneveld.net
    // +            original by: Carlos R. L. Rodrigues (http://www.jsfromhell.com)
    // +            bugfixed by: Onno Marsman
    // +             revised by: Andrea Giammarchi (http://webreflection.blogspot.com)
    // + reimplemented by: Brett Zamir (http://brett-zamir.me)
    // + reimplemented by: Alexander M Beedie
    // *                example 1: levenshtein('Kevin van Zonneveld', 'Kevin van Sommeveld');
    // *                returns 1: 3

    if (s1 == s2) {
        return 0;
    }

    var s1_len = s1.length;
    var s2_len = s2.length;
    if (s1_len === 0) {
        return s2_len;
    }
    if (s2_len === 0) {
        return s1_len;
    }

    // BEGIN STATIC
    var split = false;
    try{
        split=!('0')[0];
    } catch (e){
        split=true; // Earlier IE may not support access by string index
    }
    // END STATIC
    if (split){
        s1 = s1.split('');
        s2 = s2.split('');
    }

    var v0 = new Array(s1_len+1);
    var v1 = new Array(s1_len+1);

    var s1_idx=0, s2_idx=0, cost=0;
    for (s1_idx=0; s1_idx<s1_len+1; s1_idx++) {
        v0[s1_idx] = s1_idx;
    }
    var char_s1='', char_s2='';
    for (s2_idx=1; s2_idx<=s2_len; s2_idx++) {
        v1[0] = s2_idx;
        char_s2 = s2[s2_idx - 1];

        for (s1_idx=0; s1_idx<s1_len;s1_idx++) {
            char_s1 = s1[s1_idx];
            cost = (char_s1 == char_s2) ? 0 : 1;
            var m_min = v0[s1_idx+1] + 1;
            var b = v1[s1_idx] + 1;
            var c = v0[s1_idx] + cost;
            if (b < m_min) {
                m_min = b; }
            if (c < m_min) {
                m_min = c; }
            v1[s1_idx+1] = m_min;
        }
        var v_tmp = v0;
        v0 = v1;
        v1 = v_tmp;
    }
    return v0[s1_len];
}

/*
 *Move coach from select to Classement box
 **/
function moveCoach(){
    jQuery('#selectCoaches option:selected').each(function(){
        if( jQuery('#participant_'+jQuery(this).val()).length > 0 )
        {
            _pulsate( jQuery('#participant_'+jQuery(this).val()) );
            return;
        }
        _createRow(jQuery(this).val(),jQuery(this).html());
    });
}

/*
 * move coach to list, creation will be done once list validated
 **/
function moveNewCoach()
{
    var name = jQuery('#newCoachName').val();
    if( name =='' )return;
    var confirmed = true;
    jQuery('#selectCoaches option').each(function(){
        dist = _levenshtein(name.toLowerCase() ,jQuery(this).html().toLowerCase());
        if(dist <= 1)
        {
            confirmed = confirm('attention !! le coach "'+jQuery(this).html()+'" existe déja. Est-ce que vous êtes sur de vouloir créer le coach "'+name+'" ?'  );
        }
    });
    if(confirmed){
        _createRow('',name);
    }

}


/*
 *Remove a participant from the classement
 **/
function deleteRow(el)
{
    jQuery(el).parent().remove();
}