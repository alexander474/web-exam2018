<?php
$allowedFontFormats 	= array ('ttf','otf','woff');
$uaf_api_key			= get_option('uaf_api_key');

if (isset($_POST['submit-uaf-font'])){	
	$fontUploadFinalMsg		= '';
	$fontUploadFinalStatus 	= 'updated';
		
		if (!empty($_POST['font_name'])):
			$fontNameToStore 		= sanitize_file_name(date('ymdhis').$_POST['font_name']);
			$fontNameToStoreWithUrl = $fontNameToStore;
			if (!empty($_POST['convert_response'])):
				$convertResponseArray = json_decode(stripslashes($_POST['convert_response']), true);
				if ($convertResponseArray['global']['status'] == 'ok'):
					$neededFontFormats = array('woff','eot');
					foreach ($neededFontFormats as $neededFontFormat):
						if ($convertResponseArray[$neededFontFormat]['status'] == 'ok'):
							$fontFileContent 	= '';
							$fontFileContent 	= wp_remote_fopen($convertResponseArray[$neededFontFormat]['filename']);
							if (!empty($fontFileContent)):
								$newFileName		= $fontNameToStore.'.'.$neededFontFormat;
								$newFilePath		= $uaf_upload_dir.$newFileName;
								$fh = fopen($newFilePath, 'w') or die("can't open file. Make sure you have write permission to your upload folder");
								fwrite($fh, $fontFileContent);
								fclose($fh);
								$fontUploadMsg[$neededFontFormat]['status'] 	= 'ok';
								$fontUploadMsg[$neededFontFormat]['text']		= "Done";
							else:
								$fontUploadMsg[$neededFontFormat]['status'] 	= 'error';
								$fontUploadMsg[$neededFontFormat]['text']		= "Couldn't receive $neededFontFormat file";
							endif;
						else:
								$fontUploadMsg[$neededFontFormat]['status'] 	= 'error';
								$fontUploadMsg[$neededFontFormat]['text']		= "Problem converting to $neededFontFormat format";
						endif;
					endforeach;
				else:
					$fontUploadFinalStatus   = 'error';
					$fontUploadFinalMsg 	.= $convertResponseArray['global']['msg'].'<br/>';
				endif;
			else:
					$fontUploadFinalStatus   = 'error';
					$fontUploadFinalMsg 	 = "Convert Response is Empty. Please try again enabling alternative uploader from Additional settings below.";
			endif;
		else:
				$fontUploadFinalStatus   = 'error';
				$fontUploadFinalMsg 	 = "Font Name is empty";
		endif;
			
		
		if (!empty($fontUploadMsg)):
			foreach ($fontUploadMsg as $formatKey => $formatData):
				if ($formatData['status'] == 'error'):
					$fontUploadFinalStatus = 'error';
					$fontUploadFinalMsg   .= $formatData['text'].'<br/>';
				endif;
			endforeach;
		endif;
		
		if ($fontUploadFinalStatus != 'error'):
			$fontUploadFinalMsg   = 'Font Uploaded';
		endif;
		
		if ($fontUploadFinalStatus != 'error'):
			$fontsRawData 	= get_option('uaf_font_data');
			$fontsData		= json_decode($fontsRawData, true);
			if (empty($fontsData)):
				$fontsData = array();
			endif;
			
			$fontsData[date('ymdhis')]	= array('font_name' => sanitize_title($_POST['font_name']), 'font_path' => $fontNameToStoreWithUrl);
			$updateFontData	= json_encode($fontsData);
			update_option('uaf_font_data',$updateFontData);
			uaf_write_css();	
		endif;
}

if (isset($_GET['delete_font_key'])):
	$fontsRawData 	= get_option('uaf_font_data');
	$fontsData		= json_decode($fontsRawData, true);
	$key_to_delete	= $_GET['delete_font_key'];
	@unlink(realpath($uaf_upload_dir.$fontsData[$key_to_delete]['font_path'].'.woff'));
	@unlink(realpath($uaf_upload_dir.$fontsData[$key_to_delete]['font_path'].'.eot'));
	unset($fontsData[$key_to_delete]);
	$updateFontData	= json_encode($fontsData);
	update_option('uaf_font_data',$updateFontData);
	$fontUploadFinalStatus  = 'updated';
	$fontUploadFinalMsg 	= 'Font Deleted';
	uaf_write_css();
endif;
?>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Upload Fonts</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>

<?php if (!empty($fontUploadFinalMsg)):?>
	<div class="<?php echo $fontUploadFinalStatus; ?>" id="message"><p><?php echo $fontUploadFinalMsg ?></p></div>
<?php endif; ?>

<?php 
$fontsRawData 	= get_option('uaf_font_data');
$fontsData		= json_decode($fontsRawData, true);
?>

<p align="right"><input type="button" name="open_add_font" onClick="open_add_font();" class="button-primary" value="Add Fonts" /><br/></p>

<div id="font-upload" style="display:none;">
	<form action="admin.php?page=uaf_settings_page" id="open_add_font_form" method="post" enctype="multipart/form-data">
    	<table class="uaf_form">
        	<tr>
            	<td width="175">Font Name</td>
                <td><input type="text" name="font_name" value="" maxlength="20" class="required" style="width:200px;" /></td>
            </tr>	
            <tr>    
                <td>Font File</td>
                <td><input type="file" id="fontfile" name="fontfile" value="" class="required" accept=".woff,.ttf,.otf" /><br/>
                <?php 
				
				?>
                <em>Accepted Font Format : <?php echo join(", ",$allowedFontFormats); ?> | Font Size: Upto 15 MB</em><br/>
                
                </td>
            </tr>
            <tr>        
                <td>&nbsp;
                	
                </td>
                <td>
                <input type="hidden" name="api_key" value="<?php echo $uaf_api_key; ?>" />
                <input type="hidden" name="font_count" value="<?php echo count($fontsData); ?>" />
                <input type="hidden" name="convert_response" id="convert_response" value="" />
                <input type="submit" name="submit-uaf-font" id="submit-uaf-font" class="button-primary" value="Upload" />
                <div id="font_upload_message" class=""></div>
                <p>By clicking on Upload, you confirm that you have rights to use this font.</p>
                </td>
            </tr>
        </table>	
    </form>
    <br/><br/>
</div>

<table cellspacing="0" class="wp-list-table widefat fixed bookmarks">
	<thead>
    	<tr>
        	<th width="20">Sn</th>
            <th>Font</th>
            <th width="100">Delete</th>
        </tr>
    </thead>
    
    <tbody>
    	<?php if (!empty($fontsData)): ?>
        <?php 
		$sn = 0;
		foreach ($fontsData as $key=>$fontData):
		$sn++
		?>
        <tr>
        	<td><?php echo $sn; ?></td>
            <td><?php echo $fontData['font_name'] ?></td>
            <td><a onclick="if (!confirm('Are you sure ?')){return false;}" href="admin.php?page=uaf_settings_page&delete_font_key=<?php echo $key; ?>">Delete</a></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
        	<td colspan="3">No font found. Please click on Add Fonts to add font</td>
        </tr>
        <?php endif; ?>        
    </tbody>
    
</table>

<script>
	function open_add_font(){
		jQuery('#font-upload').toggle('fast');
		jQuery("#open_add_font_form").validate();		
	}	
</script>
<br/>
</td>
</tr>
</tbody>
</table>
<br/>
<script>
jQuery('#open_add_font_form')
  .submit(function(e){    
	var $formValid = jQuery(this);
	if(! $formValid.valid()) return false;
	
	jQuery.ajax( {
      url: '<?php echo $uaf_font_convert_server_url; ?>/font-convertor/convertor/convert.php',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false,
	  async: false,
	  beforeSend : function(){
			 jQuery('#submit-uaf-font').attr('disabled',true);
			 jQuery('#font_upload_message').attr('class','ok');
			 jQuery('#font_upload_message').html('Uploading Font. It might take few mins based on your font file size.');
		  },
	  success: function(data, textStatus, jqXHR) 
        {
            var dataReturn = JSON.parse(data);
			status = dataReturn.global.status;
			msg	   = dataReturn.global.msg;
			
			if (status == 'error'){
				jQuery('#font_upload_message').attr('class',status);
				jQuery('#font_upload_message').html(msg);
				e.preventDefault();
			} else {
				woffStatus = dataReturn.woff.status;
				eotStatus = dataReturn.eot.status;
				if (woffStatus == 'ok' && eotStatus == 'ok'){
					woffFilePath = dataReturn.woff.filename;
					eotFilePath = dataReturn.eot.filename;
					jQuery('#convert_response').val(data);
					jQuery('#font_upload_message').attr('class','ok');
					jQuery('#font_upload_message').html('Font Conversion Complete. Finalizing...');
					jQuery('#submit-uaf-font').attr('disabled',false);
					jQuery('#fontfile').remove();
				} else {
					jQuery('#font_upload_message').attr('class','error');
					jQuery('#font_upload_message').html('Problem converting font to woff/eot.');
					e.preventDefault();
				}
			}			
        },
	   error: function(jqXHR, textStatus, errorThrown) 
        {
            jQuery('#font_upload_message').attr('class','error');
			jQuery('#font_upload_message').html('Unexpected Error Occured.');
			jQuery('#submit-uaf-font').attr('disabled',false);
			e.preventDefault();
        }	
    });
   // e.preventDefault();
  });
</script>