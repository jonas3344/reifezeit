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
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rundfahrt <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>rundfahrt/transfermarkt">Transfermarkt</a></li>
		            <li><a href="<?= base_url()?>shortlist">Shortlists</a></li>
		            <li><a href="<?= base_url()?>rundfahrt/etappen">Etappen</a></li>
		            <li><a href="<?= base_url()?>rundfahrt/resultate">Resultate</a></li>
		          </ul>
		        </li>
	            <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Kader <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		            <li><a href="<?= base_url()?>kader/tag">Heutiger Kader</a></li>
		            <li><a href="<?= base_url()?>kader/kaderuebersicht">Kaderübersicht</a></li>
		            <li><a href="<?= base_url()?>planung">Kaderplanung</a></li>
		          </ul>
		        </li>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Historie <span class="caret"></span></a>
		          <ul class="dropdown-menu">
			          <li><a href="<?= base_url()?>historie/top10">Top-10</a></li>
		            <li><a href="<?= base_url()?>historie/timeline">Deine Historie</a></li>
		          </ul>
		        </li>
		        <li>
		        	<a href="<?= base_url()?>profil" role="button"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Dein Profil</a>
		        </li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
		        <li><a href="<?= base_url();?>login/logout">Logout</a></li>  
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

