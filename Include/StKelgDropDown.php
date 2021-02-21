<select name="Email">
	<option value="">Tidak Ada Data</option>
	<option value="Aktif"<?php if ($sEmail == "Aktif") { echo " selected"; } ?>>Aktif
	<option value="Titipan"<?php if ($sEmail == "Titipan") { echo " selected"; } ?>>Titipan
	<option value="Tamu"<?php if ($sEmail == "Tamu") { echo " selected"; } ?>>Tamu
	<option value="Pindah"<?php if ($sEmail == "Pindah") { echo " selected"; } ?>>Pindah
	<option value="NonAktif"<?php if ($sEmail == "NonAktif") { echo " selected"; } ?>>Warga NonAktif
	<option value="Meninggal"<?php if ($sEmail == "Meninggal") { echo " selected"; } ?>>Meninggal
</select>
