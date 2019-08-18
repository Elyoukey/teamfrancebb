function $( query ){
    return document.querySelectorAll( query );
}

/* filter a table (tableid) according to the value value */
function tablefilter( value, tableid ){
    var rows = $( '#'+tableid+' tbody tr' );
    console.log(row);
    for(var row of rows) {
        if (row.innerHTML.toLowerCase().search( value.toLowerCase() ) < 0) {
            row.style.display = 'none';
        }else{
            row.style.display = 'table-row';
        }
    }
}