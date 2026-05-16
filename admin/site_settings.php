<?php
include 'db_connect.php';
$meta = array();
$qry = $conn->query("SELECT * from system_settings limit 1");
if($qry->num_rows > 0){
	foreach($qry->fetch_array() as $k => $val){
		$meta[$k] = $val;
	}
}
 ?>
<div class="container-fluid">
	
	<div class="card col-lg-12">
		<div class="card-body">
			<form action="" id="manage-settings">
				<div class="form-group">
					<label for="name" class="control-label">System Name</label>
					<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? htmlspecialchars($meta['name']) : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="email" class="control-label">Email</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo isset($meta['email']) ? htmlspecialchars($meta['email']) : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="contact" class="control-label">Contact</label>
					<input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? htmlspecialchars($meta['contact']) : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="about" class="control-label">About Content</label>
					<textarea name="about" class="text-jqte"><?php echo isset($meta['about_content']) ? $meta['about_content'] : '' ?></textarea>

				</div>
				<div class="form-group">
					<label class="control-label">White logo (dark / hero navbar)</label>
					<p class="text-muted small mb-2">Light-colored logo for the transparent navbar on the homepage hero. Also used in the admin header.</p>
					<input type="file" class="form-control" name="logo" accept="image/*" onchange="displayLogo(this, '#logo-preview')">
				</div>
				<div class="form-group">
					<img src="<?php echo !empty($meta['logo_img']) ? '../assets/img/'.$meta['logo_img'] : '' ?>" alt="White logo preview" id="logo-preview" class="settings-logo-preview settings-logo-preview--darkbg<?php echo empty($meta['logo_img']) ? ' d-none' : '' ?>">
				</div>
				<div class="form-group">
					<label class="control-label">Dark logo (white navbar after scroll)</label>
					<p class="text-muted small mb-2">Dark-colored logo for the white navbar after scroll (and on cart, about, etc.). Optional if you only upload one logo.</p>
					<input type="file" class="form-control" name="logo_dark" accept="image/*" onchange="displayLogo(this, '#logo-dark-preview')">
				</div>
				<div class="form-group">
					<img src="<?php echo !empty($meta['logo_img_dark']) ? '../assets/img/'.$meta['logo_img_dark'] : '' ?>" alt="Dark logo preview" id="logo-dark-preview" class="settings-logo-preview<?php echo empty($meta['logo_img_dark']) ? ' d-none' : '' ?>">
				</div>
				<div class="form-group">
					<label class="control-label">Homepage Cover Image</label>
					<input type="file" class="form-control" name="img" accept="image/*" onchange="displayImg(this,$(this))">
				</div>
				<div class="form-group">
					<img src="<?php echo isset($meta['cover_img']) ? '../assets/img/'.$meta['cover_img'] :'' ?>" alt="Cover preview" id="cimg" class="settings-cover-preview">
				</div>
				<div class="form-group">
					<label class="control-label">School / Campus Gallery</label>
					<p class="text-muted small mb-2">Photos shown on the customer login and signup screens. Upload several images of your school or campus.</p>
					<input type="file" class="form-control" name="school_gallery[]" accept="image/*" multiple>
				</div>
				<?php
				$gallery_list = array();
				if(!empty($meta['school_gallery'])){
					$gallery_list = json_decode($meta['school_gallery'], true);
					if(!is_array($gallery_list)){
						$gallery_list = array();
					}
				}
				if(!empty($gallery_list)):
				?>
				<div class="form-group">
					<label class="control-label d-block">Current gallery</label>
					<div class="row school-gallery-admin">
						<?php foreach($gallery_list as $gimg): ?>
						<div class="col-6 col-md-4 col-lg-3 mb-3">
							<div class="school-gallery-item">
								<img src="../assets/img/<?php echo htmlspecialchars($gimg) ?>" alt="Gallery photo" class="img-fluid rounded">
								<label class="school-gallery-remove mt-2 mb-0">
									<input type="checkbox" name="remove_gallery[]" value="<?php echo htmlspecialchars($gimg) ?>"> Remove
								</label>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
				<center>
					<button class="btn btn-info btn-primary btn-block col-md-2">Save</button>
				</center>
			</form>
		</div>
	</div>
</div>

<style>
.settings-logo-preview {
	max-height: 140px;
	max-width: 140px;
	width: auto;
	height: auto;
	object-fit: contain;
	display: block;
	padding: 0;
	background: transparent;
}
.settings-logo-preview--darkbg {
	background: #343a40;
	padding: 0.5rem;
	border-radius: 4px;
}
.settings-cover-preview {
	width: 100%;
	max-width: 520px;
	height: 240px;
	max-height: 240px;
	object-fit: cover;
	object-position: center;
	border-radius: 4px;
	border: 1px solid #dee2e6;
}
.school-gallery-item img {
	width: 100%;
	height: 120px;
	object-fit: cover;
	border: 1px solid #dee2e6;
}
.school-gallery-remove {
	font-size: 0.85rem;
	display: block;
}
</style>

<script>
	function displayImg(input,_this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
	function displayLogo(input, previewSelector) {
    previewSelector = previewSelector || '#logo-preview';
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$(previewSelector).attr('src', e.target.result).removeClass('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
	$('.text-jqte').jqte();

	$('#manage-settings').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_settings',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			error:err=>{
				console.log(err)
			},
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.','success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})

	})
</script>
