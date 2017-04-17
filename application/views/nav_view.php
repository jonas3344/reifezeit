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
	          <a class="navbar-brand" href="<?= base_url()?>index">Reifezeit - <?= $this->config->item('sAktuelleRundfahrt');?></a>
	        </div>
	        <div id="navbar" class="collapse navbar-collapse">
	          <ul class="nav navbar-nav">
	            <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Teilnahme <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>teilnahme/anmelden">Anmelden</a></li>
		             <li><a href="<?= base_url()?>teilnahme/teilnehmer">Teilnehmer</a></li>
		            <li><a href="<?= base_url()?>teilnahme/historie">Historie</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rundfahrt <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>rundfahrt/transfermarkt">Transfermarkt</a></li>
		            <li><a href="<?= base_url()?>rundfahrt/etappen">Etappen</a></li>
		            <li><a href="<?= base_url()?>rundfahrt/resultate">Resultate</a></li>
		          </ul>
		        </li>
	            <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kader <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>kader/tag">Heutiges Kader</a></li>
		            <li><a href="<?= base_url()?>kader/uebersicht">Kaderübersicht</a></li>
		          </ul>
		        </li>
		        <li>
		        	<a href="<?= base_url()?>profil" role="button">Dein Profil</a>
		        </li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
		        <li><a href="#contact">Logout</a></li>  
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>
