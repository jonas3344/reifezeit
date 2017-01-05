	<body>
	    <nav class="navbar navbar-inverse navbar-fixed-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="#">Reifezeit Admin</a>
	        </div>
	        <div id="navbar" class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Stammdaten <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>admin/stammdaten/rundfahrt">Rundfahrt</a></li>
		            <li><a href="#">Etappen</a></li>
		            <li><a href="#">Teams</a></li>
		            <li><a href="#">Fahrer</a></li>
		            <li><a href="#">Rollen</a></li>
		            <li><a href="#">RZ User</a></li>
		            <li><a href="#">RZ Teams</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration Rundfahrt <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">Teilnehmer</a></li>
		            <li><a href="#">Transfermarkt</a></li>
		            <li><a href="#">Ausgeschiedene Fahrer</a></li>
		          </ul>
		        </li>
	            <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Auswertung <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="#">Dopingtest</a></li>
		            <li><a href="#">Resultate parsen</a></li>
		            <li><a href="#">Etappe abschliessen</a></li>
		            <li><a href="#">Resultate löschen</a></li>
		            <li><a href="#">Forencode generieren</a></li>
		            <li><a href="#">Ruhmeshalle</a></li>
		            <li><a href="#">Rundfahrt abschliessen</a></li>
		          </ul>
		        </li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
		        <li><a href="#contact">Logout</a></li>  
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>
