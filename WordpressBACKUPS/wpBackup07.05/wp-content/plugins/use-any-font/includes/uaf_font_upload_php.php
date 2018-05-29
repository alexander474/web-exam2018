<?php
$allowedFontFormats 	= array ('ttf','otf','woff');
$allowedFontSize		= 15; 
$wpAllowedMaxSize 		= wp_max_upload_size(); 
$wpAllowedMaxSizeToMB	= $wpAllowedMaxSize / 1048576 ;
if ($wpAllowedMaxSizeToMB < $allowedFontSize){
	$allowedFontSize = $wpAllowedMaxSizeToMB;
}
$allowedFontSizeinBytes	= $allowedFontSize * 1024 * 1024; // 10 MB to bytes

if (isset($_POST['submit-uaf-font'])){	
	$uaf_api_key		= get_option('uaf_api_key');
	$font_file_name 	= $_FILES['font_file']['name'];
	$font_file_details 	= pathinfo($_FILES['font_file']['name']);
	$file_extension		= strtolower($font_file_details['extension']);	
	$font_size			= $_FILES['font_file']['size'];
	$fontUploadFinalMsg		= '';
	$fontUploadFinalStatus 	= 'updated';
	
	if ((in_array($file_extension, $allowedFontFormats)) && ($font_size <= $allowedFontSizeinBytes)){
	
		$fontNameToStore 		= sanitize_file_name(date('ymdhis').$font_file_details['filename']);
		$fontNameToStoreWithUrl = $fontNameToStore;
		
		// SEND FONT CONERSION REQUEST
		@set_time_limit(0);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $uaf_font_convert_server_url.'/font-convertor/convertor/convert.php');
		curl_setopt($ch, CURLOPT_POST, true);
		$post = array(
			'fontfile' 		=> "@".$_FILES['font_file']['tmp_name'],
			'fontfileext' 	=> pathinfo($_FILES['font_file']['name'], PATHINFO_EXTENSION),
			'api_key' 		=> $uaf_api_key,
			'font_count'	=> $_POST['font_count']
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$convertResponse = curl_exec($ch);
		if(curl_errno($ch)) {
			echo 'Error: ' . curl_error($ch);
			exit();
		}
		else {
			$CrulStatinfo = curl_getinfo($ch);
			if ($CrulStatinfo['http_code'] == '200'):
				$convertResponseArray = json_decode($convertResponse, true);
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
					$fontUploadFinalMsg 	 = $convertResponse;
			endif;
		}
		
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
	} else {
		$fontUploadFinalStatus   = 'error';
		$fontUploadFinalMsg 	 = 'Only '.join(", ",$allowedFontFormats).' format and font less than '.$allowedFontSize.' Mb accepted';
	}
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
                <td><input type="file" name="font_file" id="font_file" value="" class="required" accept=".woff,.ttf,.otf" /><br/>
                <?php 
				
				?>
                <em>Accepted Font Format : <?php echo join(", ",$allowedFontFormats); ?> | Font Size: Upto <?php echo $allowedFontSize; ?>MB</em><br/>
                
                </td>
            </tr>
            <tr>        
                <td>&nbsp;
                	
                </td>
                <td><input type="hidden" name="font_count" value="<?php echo count($fontsData); ?>" /><input type="submit" name="submit-uaf-font" class="button-primary" value="Upload" />
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
</table><br/>