<?php
if (isset($_POST['submit-uaf-implement'])){
	$fontsImplementRawData 	= get_option('uaf_font_implement');
	$fontsImplementData		= json_decode($fontsImplementRawData, true);
	if (empty($fontsImplementData)):
		$fontsImplementData = array();
	endif;
	
	$fontElements 	= array();
	$fontElements[] = @join(', ',$_POST['elements']);
	$fontElements[] = @join(', ',array_filter(array_map('trim',explode("\n", trim($_POST['custom_elements'])))));
	$fontElements 	= array_filter(array_map('trim',$fontElements));
	$finalElements  = join(', ', $fontElements);
	
	if (!empty($finalElements)){
		$fontsImplementData[date('ymdhis')]	= array(
											'font_key' 		=> $_POST['font_key'], 
											'font_elements' => $finalElements
										);
		$updateFontsImplementData		= json_encode($fontsImplementData);
		update_option('uaf_font_implement',$updateFontsImplementData);
		uaf_write_css();
		$implementMsg 					= 'Font Assigned';
		
	} else {
		$implementMsg = "Couldn't assign font. Please select atleast one element or add a custom element";
	}
	
}

if (isset($_GET['delete_implement_key'])):
	$fontsImplementRawData 	= get_option('uaf_font_implement');
	$fontsImplementData		= json_decode($fontsImplementRawData, true);
	$key_to_delete			= $_GET['delete_implement_key'];
	unset($fontsImplementData[$key_to_delete]);
	$updateFontsImplementData		= json_encode($fontsImplementData);
	update_option('uaf_font_implement',$updateFontsImplementData);
	uaf_write_css();
	$implementMsg = 'Font assign removed';
endif;
?>

<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Assign Font</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>

<?php if (!empty($implementMsg)):?>
	<div class="updated" id="message"><p><?php echo $implementMsg ?></p></div>
<?php endif; ?>

<?php 
$fontsRawData 	= get_option('uaf_font_data');
$fontsData		= json_decode($fontsRawData, true);
?>

<p align="right"><input type="button" name="open_assign_font" onClick="open_assign_font();" class="button-primary" value="Assign Font" /><br/></p>

<div id="open_assign_font" style="display:none;">
	<form action="admin.php?page=uaf_settings_page"  id="open_assign_font_form" method="post">
    	<table class="uaf_form">
        	<tr>
            	<td width="175">Select Font</td>
                <td>
                	<select name="font_key" class="required" style="width:200px;">
                    	<option value="">- Select -</option>
                        <?php 
						if (!empty($fontsData)):
							foreach ($fontsData as $key=>$fontData)	: ?>
								<option value="<?php echo $key ?>"><?php echo $fontData['font_name']; ?></option>
							<?php endforeach;
						endif; 
						?>
                    </select>
                </td>
            </tr>	
            <tr>    
                <td valign="top">Select elements to assign</td>
                <td>
                	<input name="elements[]" value="body" type="checkbox" /> All (body tags)<br/>
                    <input name="elements[]" value="h1" type="checkbox" /> Headline 1 (h1 tags)<br/>
                    <input name="elements[]" value="h2" type="checkbox" /> Headline 2 (h2 tags)<br/>
                    <input name="elements[]" value="h3" type="checkbox" /> Headline 3 (h3 tags)<br/>
                    <input name="elements[]" value="h4" type="checkbox" /> Headline 4 (h4 tags)<br/>
                    <input name="elements[]" value="h5" type="checkbox" /> Headline 5 (h5 tags)<br/>
                    <input name="elements[]" value="h6" type="checkbox" /> Headline 6 (h6 tags)<br/>
                    <input name="elements[]" value="p" type="checkbox" /> Paragraphs (p tags)<br/>
                    <input name="elements[]" value="blockquote" type="checkbox" /> Blockquotes<br/>
                    <input name="elements[]" value="li" type="checkbox" /> Lists (li tags)<br/>
                    <input name="elements[]" value="a" type="checkbox" /> Hyperlink (a tags)
                </td>
            </tr>
            <tr>        
                <td valign="top">Custom Elements</td>
                <td><textarea name="custom_elements" style="width:400px; height:150px;"></textarea><br/>
					<br/>
                    <strong>Note</strong><br/>
                    Each line indicate one css element. You don't need to use any css.<br />
					<strong>Example:</strong><br/>
                    <em>#content .wrap</em><br/>
                    <em>#content p </em>
                    <br/>
                </td>
            </tr>
            <tr>        
                <td>&nbsp;</td>
                <td><input type="submit" name="submit-uaf-implement" class="button-primary" value="Assign Font" /></td>
            </tr>
        </table>	
    </form>
    <br/><br/>
</div>

<?php 
$fontsImplementRawData 	= get_option('uaf_font_implement');
$fontsImplementData		= json_decode($fontsImplementRawData, true);

?>
<table cellspacing="0" class="wp-list-table widefat fixed bookmarks">
	<thead>
    	<tr>
        	<th  width="20">Sn</th>
            <th>Font</th>
            <th>Applied To</th>
            <th width="100">Delete</th>
        </tr>
    </thead>
    
    <tbody>
    	<?php if (!empty($fontsImplementData)): ?>
        <?php 
		$sn = 0;
		foreach ($fontsImplementData as $key=>$fontImplementData):
		$sn++
		?>
        <tr>
        	<td><?php echo $sn; ?></td>
            <td><?php echo $fontsData[$fontImplementData['font_key']]['font_name']; ?></td>
            <td><?php echo $fontImplementData['font_elements'] ?></td>
            <td><a onclick="if (!confirm('Are you sure ?')){return false;}" href="admin.php?page=uaf_settings_page&delete_implement_key=<?php echo $key; ?>">Delete</a></td>
        </tr>
        <?php endforeach; ?>
        <?php else: ?>
        <tr>
        	<td colspan="4">No font assign yet. Click on Assign Font to start.</td>
        </tr>
        <?php endif; ?>        
    </tbody>
    
</table>

<script>
	function open_assign_font(){
		jQuery('#open_assign_font').toggle('fast');
		jQuery("#open_assign_font_form").validate();
	}	
</script>

<br/>
</td>
</tr>
</tbody>
</table>