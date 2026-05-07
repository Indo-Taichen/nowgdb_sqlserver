<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LaporanBulananGDB_" . $_GET['thn']."_".$_GET['bln']. ".xls"); //ganti nama sesuai keperluan
header("Pragma: no-cache");
header("Expires: 0");
//disini script laporan anda
?>
<?php
ini_set("error_reporting", 1);
include"../../koneksi.php";
$Thn2			= isset($_GET['thn']) ? $_GET['thn'] : '';
$Bln2			= isset($_GET['bln']) ? $_GET['bln'] : '';
$Bulan			= $Thn2."-".$Bln2;
if($Thn2!="" and $Bln2!=""){
$filterBulan = $Bulan; // format: YYYY-MM

$tanggalskrng = new DateTime($filterBulan . '-01');
	
// Buat objek DateTime dari awal bulan
$tanggal = new DateTime($filterBulan . '-01');	

// Kurangi satu hari untuk dapat tanggal terakhir bulan sebelumnya
$tanggal->modify('-1 day');

// Tanggal akhir bulan berjalan
$tanggalAkhirBulanBerjalan = new DateTime($filterBulan . '-01');
$tanggalAkhirBulanBerjalan->modify('last day of this month');

// Format jika ingin ditampilkan sebagai string
$akhirBulanBerjalan = $tanggalAkhirBulanBerjalan->format('Y-m-d');	

}


?>

			      <table width="100%" border="1" >
                  <thead>
                  <tr>
                    <th rowspan="2" valign="middle" style="text-align: center" height="50"><img src="https://online.indotaichen.com/nowgdb/pages/cetak/Indo.jpg" width="48" height="48" alt="Indo.jpg"/></th>
                    <th colspan="6" valign="middle" style="text-align: left"><font size="+2">LAPORAN BULANAN STOCK BENANG</font></th>
                    </tr>
                  <tr>
                    <th height="20" colspan="6" valign="middle" style="text-align: left">FW-02-GDB-03/01</th>
                    </tr>
                  <tr>
                    <th width="2%" valign="middle" style="text-align: center">&nbsp;</th>
                    <th width="7%" valign="middle" style="text-align: center">&nbsp;</th>
                    <th width="7%" valign="middle" style="text-align: center">STOCK PROSES</th>
                    <th width="7%" valign="middle" style="text-align: center">STOCK MATI</th>
                    <th width="7%" valign="middle" style="text-align: center">SAMPLE</th>
                    <th width="7%" valign="middle" style="text-align: center">JASA</th>
                    <th width="7%" valign="middle" style="text-align: center">TOTAL</th>
                    </tr>
                  </thead>
                  <tbody>	  
	  <tr>
	  <?php	
		if($Thn2!="" and $Bln2!=""){  
		$blnLaluA 	= $tanggal->format('Y-m-d');
		$blnJlnA	= $tanggalAkhirBulanBerjalan->format('Y-m-d');	
		}else{
		$blnLaluA 	= "";
		$blnJlnA	= "";	
		}
		$sqlBlnLalu = sqlsrv_query_safe($con," select 
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_proses,
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_proses,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_stkmati,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_stkmati,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_sample,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_sample
from dbnow_gdb.tblopname_11 AS td
WHERE tgl_tutup ='$blnLaluA' ");		  
    	$rLalu = ($sqlBlnLalu !== false) ? sqlsrv_fetch_array($sqlBlnLalu, SQLSRV_FETCH_ASSOC) : ['gyr_proses'=>0,'dyr_proses'=>0,'gyr_stkmati'=>0,'dyr_stkmati'=>0,'gyr_sample'=>0,'dyr_sample'=>0];
		  
		$sqlBlnJln = sqlsrv_query_safe($con," select 
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_proses,
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_proses,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_stkmati,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_stkmati,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_sample,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_sample
from dbnow_gdb.tblopname_11 AS td
WHERE tgl_tutup ='$blnJlnA' ");		  
    	$rJln = ($sqlBlnJln !== false) ? sqlsrv_fetch_array($sqlBlnJln, SQLSRV_FETCH_ASSOC) : ['gyr_proses'=>0,'dyr_proses'=>0,'gyr_stkmati'=>0,'dyr_stkmati'=>0,'gyr_sample'=>0,'dyr_sample'=>0]; 
		  
$sqlDB21Masuk = " SELECT
	SUM(CASE WHEN (x.QUALITYLEVELCODE IN('1','2') AND x.ITEMTYPECODE = 'GYR') THEN x.BASEPRIMARYQUANTITY ELSE 0 END) AS GYR_PROSES,
	SUM(CASE WHEN (x.QUALITYLEVELCODE IN('3') AND x.ITEMTYPECODE = 'GYR') THEN x.BASEPRIMARYQUANTITY ELSE 0 END) AS GYR_STKMATI
FROM
	DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.MRNDETAIL m2 ON
	m2.TRANSACTIONNUMBER = x.TRANSACTIONNUMBER
LEFT OUTER JOIN DB2ADMIN.MRNHEADER m ON
	m.CODE = m2.MRNHEADERCODE
LEFT OUTER JOIN DB2ADMIN.CUSTOMERSUPPLIERDATA n ON
	x.SUPPLIERCODE = n.CODE
LEFT OUTER JOIN DB2ADMIN.BUSINESSPARTNER b ON
	b.NUMBERID = n.BUSINESSPARTNERNUMBERID
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON
	x.FULLITEMIDENTIFIER = f.IDENTIFIER
WHERE
	YEAR(m.CHALLANDATE) = '$Thn2' 
	AND MONTH(m.CHALLANDATE) = '$Bln2'
	AND (ORDERCOUNTERCODE = 'POYRL'
		OR ORDERCOUNTERCODE = 'POYRI')
	AND x.TOKENCODE = 'RECEIPT'";

  $stmt1Masuk   = db2_exec($conn1,$sqlDB21Masuk, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21Masuk = db2_fetch_assoc($stmt1Masuk); 
		  
$sqlDB21MasukSAMPLE = " SELECT  
SUM(CASE WHEN (x.ITEMTYPECODE = 'GYR') THEN x.BASEPRIMARYQUANTITY ELSE 0 END) AS GYR_SAMPLE
FROM DB2ADMIN.STOCKTRANSACTION x
LEFT OUTER JOIN DB2ADMIN.MRNDETAIL m ON x.TRANSACTIONNUMBER =m.TRANSACTIONNUMBER
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a1 ON x.ABSUNIQUEID = a1.UNIQUEID AND a1.NAMENAME = 'KetSampleGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a2 ON x.ABSUNIQUEID = a2.UNIQUEID AND a2.NAMENAME = 'SuratJlnGYR'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE a3 ON x.ABSUNIQUEID = a3.UNIQUEID AND a3.NAMENAME = 'SjPoGYR'
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER f ON x.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN LOT c ON x.LOTCODE = c.CODE AND c.COMPANYCODE = '100' AND 
x.ITEMTYPECODE = c.ITEMTYPECODE AND
x.DECOSUBCODE01= c.DECOSUBCODE01 AND
x.DECOSUBCODE02= c.DECOSUBCODE02 AND
x.DECOSUBCODE03= c.DECOSUBCODE03 AND
x.DECOSUBCODE04= c.DECOSUBCODE04 AND
x.DECOSUBCODE05= c.DECOSUBCODE05 AND
x.DECOSUBCODE06= c.DECOSUBCODE06 AND
x.DECOSUBCODE07= c.DECOSUBCODE07 AND
x.DECOSUBCODE08= c.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.BALANCE bc ON bc.ELEMENTSCODE = x.ITEMELEMENTCODE 
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA d ON c.SUPPLIERCODE =d.CODE AND d.COMPANYCODE = '100' AND d.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER e ON d.BUSINESSPARTNERNUMBERID =e.NUMBERID
WHERE x.ITEMTYPECODE = 'GYR'  
AND x.LOGICALWAREHOUSECODE='M011' 
AND ((x.TEMPLATECODE = 'OPN' AND a1.VALUESTRING = '1') OR x.TEMPLATECODE = '101') 
AND YEAR(x.TRANSACTIONDATE) ='$Thn2'
AND MONTH(x.TRANSACTIONDATE) = '$Bln2'";

  $stmt1MasukSAMPLE   = db2_exec($conn1,$sqlDB21MasukSAMPLE, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21MasukSAMPLE = db2_fetch_assoc($stmt1MasukSAMPLE); 
		  
$sqlDB21MasukWARNA = " SELECT 
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('1','2') AND s.ITEMTYPECODE = 'DYR') THEN s.BASEPRIMARYQUANTITY ELSE 0 END) AS DYR_PROSES,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'DYR') THEN s.BASEPRIMARYQUANTITY ELSE 0 END) AS DYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'DYR') THEN s.BASEPRIMARYQUANTITY ELSE 0 END) AS DYR_SAMPLE
  FROM DB2ADMIN.INTERNALDOCUMENTLINE x
  LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE AND 
  x.ORDERLINE=s.ORDERLINE AND x.ITEMTYPEAFICODE =s.ITEMTYPECODE AND x.INTDOCPROVISIONALCOUNTERCODE =s.ORDERCOUNTERCODE
  LEFT OUTER JOIN FULLITEMKEYDECODER f ON
  s.FULLITEMIDENTIFIER = f.IDENTIFIER
  LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
  WHERE 			   
  YEAR(s.TRANSACTIONDATE) ='$Thn2'  AND
  MONTH(s.TRANSACTIONDATE) = '$Bln2' AND
  NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND
  x.ITEMTYPEAFICODE ='DYR' AND
  s.LOGICALWAREHOUSECODE='M011' AND
  NOT x.ORDERLINE IS NULL AND 
  (INTDOCPROVISIONALCOUNTERCODE='I02P50' OR INTDOCPROVISIONALCOUNTERCODE='I02M90')";

  $stmt1MasukWARNA   = db2_exec($conn1,$sqlDB21MasukWARNA, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21MasukWARNA = db2_fetch_assoc($stmt1MasukWARNA);
$sqlDB21MasukRETUR = " SELECT	
	SUM(CASE 
            WHEN STOCKTRANSACTION.QUALITYLEVELCODE IN ('2', '5') 
            THEN STOCKTRANSACTION.BASEPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_PROSES,

    SUM(CASE 
            WHEN STOCKTRANSACTION.QUALITYLEVELCODE = '3' 
            THEN STOCKTRANSACTION.BASEPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_STKMATI,

    SUM(CASE 
            WHEN STOCKTRANSACTION.QUALITYLEVELCODE = '4' 
            THEN STOCKTRANSACTION.BASEPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_SAMPLE
FROM
	DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON
	INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = STOCKTRANSACTION.ORDERCODE
	AND 
INTERNALDOCUMENTLINE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
    AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE
	AND INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE = STOCKTRANSACTION.ORDERCOUNTERCODE
	AND INTERNALDOCUMENTLINE.SUBCODE01  = STOCKTRANSACTION.DECOSUBCODE01
	AND INTERNALDOCUMENTLINE.SUBCODE02  = STOCKTRANSACTION.DECOSUBCODE02
	AND INTERNALDOCUMENTLINE.SUBCODE03  = STOCKTRANSACTION.DECOSUBCODE03
	AND INTERNALDOCUMENTLINE.SUBCODE04  = STOCKTRANSACTION.DECOSUBCODE04
	AND INTERNALDOCUMENTLINE.SUBCODE05  = STOCKTRANSACTION.DECOSUBCODE05
	AND INTERNALDOCUMENTLINE.SUBCODE06  = STOCKTRANSACTION.DECOSUBCODE06
	AND INTERNALDOCUMENTLINE.SUBCODE07  = STOCKTRANSACTION.DECOSUBCODE07
	AND INTERNALDOCUMENTLINE.SUBCODE08  = STOCKTRANSACTION.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
	STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN LOT ON
	STOCKTRANSACTION.LOTCODE = LOT.CODE
	AND LOT.COMPANYCODE = '100'
	AND 
STOCKTRANSACTION.ITEMTYPECODE = LOT.ITEMTYPECODE
	AND
STOCKTRANSACTION.DECOSUBCODE01 = LOT.DECOSUBCODE01
	AND
STOCKTRANSACTION.DECOSUBCODE02 = LOT.DECOSUBCODE02
	AND
STOCKTRANSACTION.DECOSUBCODE03 = LOT.DECOSUBCODE03
	AND
STOCKTRANSACTION.DECOSUBCODE04 = LOT.DECOSUBCODE04
	AND
STOCKTRANSACTION.DECOSUBCODE05 = LOT.DECOSUBCODE05
	AND
STOCKTRANSACTION.DECOSUBCODE06 = LOT.DECOSUBCODE06
	AND
STOCKTRANSACTION.DECOSUBCODE07 = LOT.DECOSUBCODE07
	AND
STOCKTRANSACTION.DECOSUBCODE08 = LOT.DECOSUBCODE08
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA ON
	LOT.SUPPLIERCODE = CUSTOMERSUPPLIERDATA.CODE
	AND CUSTOMERSUPPLIERDATA.COMPANYCODE = '100'
	AND CUSTOMERSUPPLIERDATA.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER ON
	CUSTOMERSUPPLIERDATA.BUSINESSPARTNERNUMBERID = BUSINESSPARTNER.NUMBERID
WHERE
	(INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETUR'
		OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE = 'RETURAN')
	AND STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M011'
	AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = 'GYR'
AND YEAR(STOCKTRANSACTION.TRANSACTIONDATE) = '$Thn2'
AND MONTH(STOCKTRANSACTION.TRANSACTIONDATE) = '$Bln2'
	AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL ";

  $stmt1MasukRETUR   = db2_exec($conn1,$sqlDB21MasukRETUR, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21MasukRETUR = db2_fetch_assoc($stmt1MasukRETUR); 
		  
$sqlDB21MasukWarnaRETUR = " SELECT 
SUM(STOCKTRANSACTION.BASEPRIMARYQUANTITY) AS DYR_PROSES
FROM DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION 
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE=STOCKTRANSACTION.ORDERCODE AND 
INTERNALDOCUMENTLINE.ORDERLINE=STOCKTRANSACTION.ORDERLINE 
AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE = STOCKTRANSACTION.ITEMTYPECODE
AND INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE = STOCKTRANSACTION.ORDERCOUNTERCODE
	AND INTERNALDOCUMENTLINE.SUBCODE01  = STOCKTRANSACTION.DECOSUBCODE01
	AND INTERNALDOCUMENTLINE.SUBCODE02  = STOCKTRANSACTION.DECOSUBCODE02
	AND INTERNALDOCUMENTLINE.SUBCODE03  = STOCKTRANSACTION.DECOSUBCODE03
	AND INTERNALDOCUMENTLINE.SUBCODE04  = STOCKTRANSACTION.DECOSUBCODE04
	AND INTERNALDOCUMENTLINE.SUBCODE05  = STOCKTRANSACTION.DECOSUBCODE05
	AND INTERNALDOCUMENTLINE.SUBCODE06  = STOCKTRANSACTION.DECOSUBCODE06
	AND INTERNALDOCUMENTLINE.SUBCODE07  = STOCKTRANSACTION.DECOSUBCODE07
	AND INTERNALDOCUMENTLINE.SUBCODE08  = STOCKTRANSACTION.DECOSUBCODE08
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN LOT  ON STOCKTRANSACTION.LOTCODE = LOT.CODE AND LOT.COMPANYCODE = '100' AND 
STOCKTRANSACTION.ITEMTYPECODE = LOT.ITEMTYPECODE AND
STOCKTRANSACTION.DECOSUBCODE01= LOT.DECOSUBCODE01 AND
STOCKTRANSACTION.DECOSUBCODE02= LOT.DECOSUBCODE02 AND
STOCKTRANSACTION.DECOSUBCODE03= LOT.DECOSUBCODE03 AND
STOCKTRANSACTION.DECOSUBCODE04= LOT.DECOSUBCODE04 AND
STOCKTRANSACTION.DECOSUBCODE05= LOT.DECOSUBCODE05 AND
STOCKTRANSACTION.DECOSUBCODE06= LOT.DECOSUBCODE06 AND
STOCKTRANSACTION.DECOSUBCODE07= LOT.DECOSUBCODE07 AND
STOCKTRANSACTION.DECOSUBCODE08= LOT.DECOSUBCODE08
LEFT OUTER JOIN CUSTOMERSUPPLIERDATA  ON LOT.SUPPLIERCODE =CUSTOMERSUPPLIERDATA.CODE AND CUSTOMERSUPPLIERDATA.COMPANYCODE = '100' AND CUSTOMERSUPPLIERDATA.TYPE = '2'
LEFT OUTER JOIN BUSINESSPARTNER ON CUSTOMERSUPPLIERDATA.BUSINESSPARTNERNUMBERID =BUSINESSPARTNER.NUMBERID
WHERE (INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETUR' OR INTERNALDOCUMENTLINE.EXTERNALREFERENCE='RETURAN') AND STOCKTRANSACTION.LOGICALWAREHOUSECODE ='M011' AND INTERNALDOCUMENTLINE.ITEMTYPEAFICODE='DYR'
AND YEAR(STOCKTRANSACTION.TRANSACTIONDATE) = '$Thn2'
AND MONTH(STOCKTRANSACTION.TRANSACTIONDATE) = '$Bln2' 
AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
 ";

  $stmt1MasukWarnaRETUR   = db2_exec($conn1,$sqlDB21MasukWarnaRETUR, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21MasukWarnaRETUR = db2_fetch_assoc($stmt1MasukWarnaRETUR);		  
		  
$sqlDB21Keluar = " SELECT 
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('1','2') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_PROSES,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_SAMPLE
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE AND 
x.ORDERLINE=s.ORDERLINE 
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
s.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
WHERE 
YEAR(x.CONDITIONRETRIEVINGDATE) = '$Thn2' AND
MONTH(x.CONDITIONRETRIEVINGDATE) = '$Bln2' AND 
x.ITEMTYPEAFICODE ='GYR' AND
s.LOGICALWAREHOUSECODE='M011' AND 
NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND 
NOT x.ORDERLINE IS NULL ";

  $stmt1Keluar   = db2_exec($conn1,$sqlDB21Keluar, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21Keluar = db2_fetch_assoc($stmt1Keluar); 
		  
$sqlDB21KeluarWarna = " SELECT 
              SUM(CASE WHEN (s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_PROSES
FROM DB2ADMIN.INTERNALDOCUMENTLINE x
LEFT OUTER JOIN STOCKTRANSACTION s ON x.INTDOCUMENTPROVISIONALCODE=s.ORDERCODE AND 
x.ORDERLINE=s.ORDERLINE 
LEFT OUTER JOIN FULLITEMKEYDECODER f ON
s.FULLITEMIDENTIFIER = f.IDENTIFIER
LEFT OUTER JOIN ADSTORAGE a ON a.UNIQUEID =x.ABSUNIQUEID AND a.NAMENAME ='SuppName'
WHERE 
YEAR(x.CONDITIONRETRIEVINGDATE) = '$Thn2' AND
MONTH(x.CONDITIONRETRIEVINGDATE) = '$Bln2' AND  
x.ITEMTYPEAFICODE ='DYR' AND
s.LOGICALWAREHOUSECODE='M011' AND 
NOT x.EXTERNALREFERENCE LIKE '%RETUR%' AND 
NOT x.ORDERLINE IS NULL AND 
INTDOCPROVISIONALCOUNTERCODE='I02M01' ";

  $stmt1KeluarWarna   = db2_exec($conn1,$sqlDB21KeluarWarna, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21KeluarWarna = db2_fetch_assoc($stmt1KeluarWarna);	
		  
$sqlDB21KeluarSS = " SELECT 
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_SAMPLE,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_SAMPLE
FROM STOCKTRANSACTION s 
LEFT OUTER JOIN INTERNALDOCUMENTLINE il ON
		il.INTDOCUMENTPROVISIONALCODE = s.ORDERCODE AND il.ORDERLINE = s.ORDERLINE
WHERE
s.TEMPLATECODE = '201'
AND s.COMPANYCODE ='100'
AND s.ITEMTYPECODE IN('GYR','DYR')
AND s.LOGICALWAREHOUSECODE='M011'
AND NOT il.ORDERLINE IS NULL
AND YEAR(s.TRANSACTIONDATE) ='$Thn2'
AND MONTH(s.TRANSACTIONDATE) = '$Bln2' ";

  $stmt1KeluarSS   = db2_exec($conn1,$sqlDB21KeluarSS, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21KeluarSS = db2_fetch_assoc($stmt1KeluarSS);		  

$sqlDB21KeluarRetur = " SELECT	
   SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'DYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE IN ('2', '5')
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS DYR_PROSES,

    SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'DYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE = '3'
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS DYR_STKMATI,

    SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'DYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE = '4'
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS DYR_SAMPLE,

    SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'GYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE IN ('2', '5')
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_PROSES,

    SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'GYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE = '3'
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_STKMATI,

    SUM(CASE 
            WHEN STOCKTRANSACTION.ITEMTYPECODE = 'GYR'
             AND STOCKTRANSACTION.QUALITYLEVELCODE = '4'
            THEN STOCKTRANSACTION.USERPRIMARYQUANTITY 
            ELSE 0 
        END) AS GYR_SAMPLE
FROM
		DB2ADMIN.STOCKTRANSACTION STOCKTRANSACTION
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENTLINE INTERNALDOCUMENTLINE ON
		INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE = STOCKTRANSACTION.ORDERCODE
	AND 
INTERNALDOCUMENTLINE.ORDERLINE = STOCKTRANSACTION.ORDERLINE
LEFT OUTER JOIN DB2ADMIN.INTERNALDOCUMENT INTERNALDOCUMENT ON
		INTERNALDOCUMENT.PROVISIONALCOUNTERCODE = INTERNALDOCUMENTLINE.INTDOCPROVISIONALCOUNTERCODE
	AND 
INTERNALDOCUMENT.PROVISIONALCODE = INTERNALDOCUMENTLINE.INTDOCUMENTPROVISIONALCODE
LEFT OUTER JOIN DB2ADMIN.FULLITEMKEYDECODER FULLITEMKEYDECODER ON
		STOCKTRANSACTION.FULLITEMIDENTIFIER = FULLITEMKEYDECODER.IDENTIFIER
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE ON
		INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE.UNIQUEID
	AND ADSTORAGE.NAMENAME = 'SuppName'
LEFT OUTER JOIN DB2ADMIN.ADSTORAGE ADSTORAGE2 ON
		INTERNALDOCUMENTLINE.ABSUNIQUEID = ADSTORAGE2.UNIQUEID
	AND ADSTORAGE2.NAMENAME = 'Satuan'
WHERE
		INTERNALDOCUMENT.EXTERNALREFERENCE LIKE '%RETUR%'
	AND 
STOCKTRANSACTION.LOGICALWAREHOUSECODE = 'M011'
	AND
YEAR(INTERNALDOCUMENT.EXTERNALREFERENCEDATE) = '$Thn2'
AND
MONTH(INTERNALDOCUMENT.EXTERNALREFERENCEDATE) = '$Bln2'
	AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL ";

  $stmt1KeluarRetur   = db2_exec($conn1,$sqlDB21KeluarRetur, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21KeluarRetur = db2_fetch_assoc($stmt1KeluarRetur);	
		  
$sqlDB21Jual = " SELECT 
    SUM(CASE WHEN (s.QUALITYLEVELCODE IN('1','2') AND s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_PROSES,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_SAMPLE,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('1','2') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_PROSES,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('3') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_STKMATI,
	SUM(CASE WHEN (s.QUALITYLEVELCODE IN('4') AND s.ITEMTYPECODE = 'GYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS GYR_SAMPLE
FROM STOCKTRANSACTION s 
WHERE
s.TEMPLATECODE = 'S02'
AND s.COMPANYCODE ='100'
AND s.ITEMTYPECODE IN('GYR','DYR')
AND s.LOGICALWAREHOUSECODE='M011'
AND YEAR(s.TRANSACTIONDATE) ='$Thn2'
AND MONTH(s.TRANSACTIONDATE) = '$Bln2' ";

  $stmt1Jual   = db2_exec($conn1,$sqlDB21Jual, array('cursor'=>DB2_SCROLLABLE));
  $rowdb21Jual = db2_fetch_assoc($stmt1Jual);		  
		 ?> 
	  <td rowspan="2" align="center">STOCK BULAN LALU<br>
	    <?php 
		  if($Thn2!="" and $Bln2!=""){
		  // Tampilkan hasil		  
		  echo $tanggal->format('F Y'); 
		  }
		?></td>
	  <td align="center">WARNA</td>
	  <td align="right"><?php echo number_format($rLalu['dyr_proses'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_stkmati'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_sample'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_jasa'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_proses']+$rLalu['dyr_stkmati']+$rLalu['dyr_sample']+$rLalu['dyr_jasa'],2);?></td>
	  </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rLalu['gyr_proses'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_stkmati'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_sample'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_proses']+$rLalu['gyr_stkmati']+$rLalu['gyr_sample']+$rLalu['gyr_jasa'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">PINDAHAN MASUK</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">SELISIH ERP</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">MASUK</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWARNA['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukSAMPLE['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_PROSES']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21Masuk['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">RETUR</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_STKMATI']+$rowdb21MasukWarnaRETUR['DYR_SAMPLE']+$rowdb21MasukWarnaRETUR['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_PROSES']+$rowdb21MasukRETUR['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">KELUAR</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarSS['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarSS['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarSS['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_PROSES']+$rowdb21Keluar['GYR_STKMATI']+$rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarSS['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">RETUR</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_PROSES']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_PROSES']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">JUAL</td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_PROSES']+$rowdb21Jual['DYR_STKMATI']+$rowdb21Jual['DYR_SAMPLE']+$rowdb21Jual['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_PROSES']+$rowdb21Jual['GYR_STKMATI']+$rowdb21Jual['GYR_SAMPLE']+$rowdb21Jual['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">AKHIR<br>
	      <?php 
		  if($Thn2!="" and $Bln2!=""){	
		  // Tampilkan hasil
		  $tanggalskrng->format('Y-m-d'); // Output: 2025-03-31
		  echo $tanggalskrng->format('F Y');
		  } 		  	
		?></td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_proses']+$rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_PROSES'])-($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarRetur['DYR_PROSES']+$rowdb21Jual['DYR_PROSES']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_stkmati']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukRETUR['DYR_STKMATI'])-($rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21Jual['DYR_STKMATI']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_sample']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWarnaRETUR['DYR_SAMPLE'])-($rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21Jual['DYR_SAMPLE']),2);?></td>
	    <td align="right"><?php echo number_format($rJln['dyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format((($rLalu['dyr_proses']+$rLalu['dyr_stkmati']+$rLalu['dyr_sample']+$rLalu['dyr_jasa'])+($rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_PROSES']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWARNA['DYR_JASA'])+($rowdb21MasukRETUR['DYR_PROSES']+$rowdb21MasukRETUR['DYR_STKMATI']+$rowdb21MasukRETUR['DYR_SAMPLE']+$rowdb21MasukRETUR['DYR_JASA']))-(($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarSS['DYR_JASA'])+($rowdb21KeluarRetur['DYR_PROSES']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_JASA'])+($rowdb21Jual['DYR_PROSES']+$rowdb21Jual['DYR_STKMATI']+$rowdb21Jual['DYR_SAMPLE']+$rowdb21Jual['DYR_JASA'])),2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_proses']+$rowdb21Masuk['GYR_PROSES']+$rowdb21MasukRETUR['GYR_PROSES'])-($rowdb21Keluar['GYR_PROSES']+$rowdb21KeluarRetur['GYR_PROSES']+$rowdb21Jual['GYR_PROSES']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_stkmati']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_STKMATI'])-($rowdb21Keluar['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21Jual['GYR_STKMATI']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_sample']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_SAMPLE'])-($rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21Jual['GYR_SAMPLE']),2);?></td>
	    <td align="right"><?php echo number_format($rJln['gyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format((($rLalu['gyr_proses']+$rLalu['gyr_stkmati']+$rLalu['gyr_sample']+$rLalu['gyr_jasa'])+($rowdb21Masuk['GYR_PROSES']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21Masuk['GYR_JASA'])+($rowdb21MasukRETUR['GYR_PROSES']+$rowdb21MasukRETUR['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_JASA']))-(($rowdb21Keluar['GYR_PROSES']+$rowdb21Keluar['GYR_STKMATI']+$rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarSS['GYR_JASA'])+($rowdb21KeluarRetur['GYR_PROSES']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_JASA'])+($rowdb21Jual['GYR_PROSES']+$rowdb21Jual['GYR_STKMATI']+$rowdb21Jual['GYR_SAMPLE']+$rowdb21Jual['GYR_JASA'])),2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2" align="center">STOCK OPNAME<br>
		<?php 
		  if($Thn2!="" and $Bln2!=""){	
		  // Tampilkan hasil
		  $tanggalskrng->format('Y-m-d'); // Output: 2025-03-31
		  echo $tanggalskrng->format('F Y');} 
		?></td>
	    <td align="center">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="center">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="center">&nbsp;</td>
	    <td align="center">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="center">&nbsp;</td>
	    <td align="center">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    <td align="right">&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="center">&nbsp;</td>
	    <td colspan="2" align="center" valign="top">Dibuat Oleh :</td>
	    <td colspan="2" align="center" valign="top">Diperiksa Oleh :</td>
	    <td colspan="2" align="center" valign="top">Mengetahui:</td>
	    </tr>
	  <tr>
	    <td align="left" valign="top">NAMA</td>
	    <td colspan="2" align="center" valign="middle">N Gadish A</td>
	    <td colspan="2" align="center" valign="middle">N/A</td>
	    <td colspan="2" align="center" valign="middle">Redy Kurnianto</td>
	    </tr>
	  <tr>
	    <td align="left" valign="top">JABATAN</td>
	    <td colspan="2" align="center" valign="middle">Staff</td>
	    <td colspan="2" align="center" valign="middle">N/A</td>
	    <td colspan="2" align="center" valign="middle">Leader</td>
	    </tr>
	  <tr>
	    <td align="left" valign="top">TANGGAL</td>
	    <td colspan="2" align="center">&nbsp;</td>
	    <td colspan="2" align="right">&nbsp;</td>
	    <td colspan="2" align="right">&nbsp;</td>
	    </tr>
	  <tr>
	    <td align="left" valign="top">TANDA TANGAN<p>&nbsp;</p>
   					<p>&nbsp;</p></td>
	    <td colspan="2" align="center" valign="middle">&nbsp;</td>
	    <td colspan="2" align="center" valign="middle">N/A</td>
	    <td colspan="2" align="center" valign="middle">&nbsp;</td>
	    </tr>
	              </tbody>
       <tfoot>				
					</tfoot>
                </table>
              
