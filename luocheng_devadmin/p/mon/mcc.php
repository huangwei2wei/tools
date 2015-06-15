<?php
require("../../kernel/base/lib/public.php");
require(D_R."res/data/base_monster_list.php");
?>
<form id="form1" name="form1" method="post" action="">
  <table width="230" border="1">
    <tr>
      <td width="35%" align="center">怪物</td>
      <td width="65%"><select name="mlist" id="mlist">
            <?
          foreach((array)$base_monster_list as $key=>$value)
		  {
		  ?>
            <option value="<?=$value["id"]?>@#<?=$value["monster_name"]?>"><?=$value["monster_name"]?></option>
           <?
		   }
		   ?>
            </select></td>
    </tr>
    <tr>
      <td align="center">范围</td>
      <td><input name="xytoxy" type="text" id="xytoxy" size="20" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center"><input onclick="tijiao()" type="button" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
