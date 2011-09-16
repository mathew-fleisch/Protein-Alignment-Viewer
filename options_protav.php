<link rel="stylesheet" type="text/css" href="/inc/protein_alignment/style.css"/>
<script type="text/javascript" src="/inc/protein_alignment/display.js"></script>
<link rel="stylesheet" type="text/css" href="/inc/js/jquery.ui.css"/>
<script type="text/javascript" src="/inc/js/jquery.js"></script>
<script type="text/javascript" src="/inc/js/jquery.ui.js"></script>

<div>
	<div id="optionsWrapper">
		<form method="post" id="optionsForm">
		<p>
		<span id="optionsTitle">Display Options:</span>
			<div class="animal_options">
				<ul class="protav_animals">
					<li class="protav_animal">
						Chimpanzee
						<input type="checkbox" id="animalBoxes" name="show_animal" value="chimpanzee" checked>
					</li>
					<li class="protav_animal">
						Florida Lancelet
						<input type="checkbox" id="animalBoxes" name="show_animal" value="lancelet" checked>
					</li>
					<li class="protav_animal">
						Fruit Fly
						<input type="checkbox" id="animalBoxes" name="show_animal" value="fly" checked>
					</li>
					<li class="protav_animal">
						Giant Panda
						<input type="checkbox" id="animalBoxes" name="show_animal" value="panda" checked>
					</li>
					<li class="protav_animal">
						Horse
						<input type="checkbox" id="animalBoxes" name="show_animal" value="horse" checked>
					</li>
					<li class="protav_animal">
						Human
						<input type="checkbox" id="animalBoxes" name="show_animal" value="human" checked>
					</li>
					<li class="protav_animal">
						Japanese Pufferfish
						<input type="checkbox" id="animalBoxes" name="show_animal" value="pufferfish" checked>
					</li>
					<li class="protav_animal">
						Mouse
						<input type="checkbox" id="animalBoxes" name="show_animal" value="mouse" checked>
					</li>
					<li class="protav_animal">
						Pig
						<input type="checkbox" id="animalBoxes" name="show_animal" value="pig" checked>
					</li>
					<li class="protav_animal">
						Rat
						<input type="checkbox" id="animalBoxes" name="show_animal" value="rat" checked>
					</li>
					<li class="protav_animal">
						Sheep
						<input type="checkbox" id="animalBoxes" name="show_animal" value="sheep" checked>
					</li>
					<li class="protav_animal">
						Sumatran Orangutan
						<input type="checkbox" id="animalBoxes" name="show_animal" value="orangutan" checked>
					</li>
					<li class="protav_animal">
						White-Tufted-Ear Marmoset
						<input type="checkbox" id="animalBoxes" name="show_animal" value="marmoset" checked>
					</li>
				</ul>
			</div>
			<div class="dynamic_options">
				PTMS
				<input type="checkbox" id="dynamicBoxes" name="ptms" value="show_ptms" checked>
				<br>
				Highlight Row
				<input type="checkbox" id="dynamicBoxes" name="row" value="highlight_row" checked>
				<br>
				Hightlight Column
				<input type="checkbox" id="dynamicBoxes" name="ptms" value="highlight_col" checked>
			</div>
			<div class="buttonWrapper">
				<input type="button" value="Check All" class="myButtons" id="check_all">
				<input type="button" value="Reload" class="myButtons" id="activate_protav_ajax">
			</div>
		</form>
	</div>
</div>
