<?php
$mAddress = '';
$mAddress .= $this->address ;
$mAddress .= ', ';
$mAddress .= $this->city;
$mAddress .= ' ';
$mAddress .= $this->postalcode;
$mAddress .= ' ';
$mAddress .= $this->country;
?>
<div class="map">
    <iframe id="Gmap" width="100%" height="200" src="https://www.google.com/maps/embed/v1/place?zoom=5&key=AIzaSyC7D3C3lOwEzg_9MYhpWoAFdO0h1q6IU2Y&q=<?php echo urlencode($mAddress);?>"></iframe>
</div>