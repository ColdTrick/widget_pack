<?php

echo elgg_view('flexslider/flexslider.css');
?>
.flex-caption {
  width: 100%;
  padding: 20px;
  left: 0;
  bottom: 0;
  background: rgba(0,0,0,.5);
  color: #fff;
  text-shadow: 0 -1px 0 rgba(0,0,0,.3);
  position: absolute;
  bottom: 0;
}
.flexslider {
	margin: 0;
}
.flexslider .slides > li {
	position: relative;
}

.flex-direction-nav a {
	line-height: 40px;
}


.image-slider-module {
	.elgg-body {
		display: none;
		
		.elgg-field {
			display: flex;
			
			> .elgg-field-label {
				flex-basis: 150px;
			}
			> .elgg-field-input {
				flex-basis: 100%;
			}
			> .elgg-field-help {
				display: none;
			}
		}
	}
}
