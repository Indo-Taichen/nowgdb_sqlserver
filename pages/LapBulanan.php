<?php
$Thn2			= isset($_POST['thn']) ? $_POST['thn'] : '';
$Bln2			= isset($_POST['bln']) ? $_POST['bln'] : '';
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
<!-- Main content -->
      <div class="container-fluid">
		<form role="form" method="post" enctype="multipart/form-data" name="form1">  
		<div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Filter Data Gudang Benang</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->		  
          <div class="card-body">
             <div class="form-group row">            
			<div class="col-sm-1">
                	<select name="thn" class="form-control form-control-sm  select2"> 
                	<option value="">Pilih Tahun</option>
        <?php
                $thn_skr = date('Y');
                for ($x = $thn_skr; $x >= 2022; $x--) {
                ?>
        <option value="<?php echo $x ?>" <?php if($Thn2!=""){if($Thn2==$x){echo "SELECTED";}}else{if($x==$thn_skr){echo "SELECTED";}} ?>><?php echo $x ?></option>
        <?php
                }
   ?>
                	</select>
                	</div>
		       	<div class="col-sm-2">
                	<select name="bln" class="form-control form-control-sm  select2"> 
                	<option value="">Pilih Bulan</option>
					<option value="01" <?php if($Bln2=="01"){ echo "SELECTED";}?>>Januari</option>
					<option value="02" <?php if($Bln2=="02"){ echo "SELECTED";}?>>Febuari</option>
					<option value="03" <?php if($Bln2=="03"){ echo "SELECTED";}?>>Maret</option>
					<option value="04" <?php if($Bln2=="04"){ echo "SELECTED";}?>>April</option>
					<option value="05" <?php if($Bln2=="05"){ echo "SELECTED";}?>>Mei</option>
					<option value="06" <?php if($Bln2=="06"){ echo "SELECTED";}?>>Juni</option>
					<option value="07" <?php if($Bln2=="07"){ echo "SELECTED";}?>>Juli</option>
					<option value="08" <?php if($Bln2=="08"){ echo "SELECTED";}?>>Agustus</option>
					<option value="09" <?php if($Bln2=="09"){ echo "SELECTED";}?>>September</option>
					<option value="10" <?php if($Bln2=="10"){ echo "SELECTED";}?>>Oktober</option>
					<option value="11" <?php if($Bln2=="11"){ echo "SELECTED";}?>>November</option>
					<option value="12" <?php if($Bln2=="12"){ echo "SELECTED";}?>>Desember</option>	
                	</select>
                	</div>		
				 <!-- /.input group -->
			
              	  
          </div>
			  
				 
			 
          </div>		  
		  <div class="card-footer"> 
			  <button class="btn btn-info" type="submit">Cari Data</button>
		  </div>	
		  <!-- /.card-body -->          
        </div>  
		
		<div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Detail Laporan Bulanan Benang</h3>
				<a href="javascript:void(0);" class="btn btn-sm btn-default float-right mx-1"
          onclick="cetak_cek('cetakLapBulananGDBExcel');"><i class="fa fa-file"></i> To Excel</a>  
          </div>
              <!-- /.card-header -->
              <div class="card-body">
			      <table id="example16" width="100%" class="table table-sm table-bordered table-striped" style="font-size: 11px; text-align: center;">
                  <thead>
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
			$sqlBlnLalu = sqlsrv_query($con," select 
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_proses,
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_proses,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_stkmati,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_stkmati,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_sample,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_sample
	from dbnow_gdb.tblopname_11 AS td
	WHERE tgl_tutup ='$blnLaluA' ");		  
	    	$rLalu = sqlsrv_fetch_array($sqlBlnLalu, SQLSRV_FETCH_ASSOC);
			  
			$sqlBlnJln = sqlsrv_query($con," select 
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_proses,
SUM(CASE WHEN (td.grd IN('','A','B') AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_proses,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_stkmati,
SUM(CASE WHEN (td.grd = 'C' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_stkmati,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'GYR') THEN td.weight ELSE 0 END) AS gyr_sample,
SUM(CASE WHEN (td.grd = 'D' AND td.tipe = 'DYR') THEN td.weight ELSE 0 END) AS dyr_sample
	from dbnow_gdb.tblopname_11 AS td
	WHERE tgl_tutup ='$blnJlnA' ");		  
	    	$rJln = sqlsrv_fetch_array($sqlBlnJln, SQLSRV_FETCH_ASSOC); 
		  
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
	AND NOT INTERNALDOCUMENTLINE.ORDERLINE IS NULL
 ";

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
              SUM(CASE WHEN ( s.ITEMTYPECODE = 'DYR') THEN s.USERPRIMARYQUANTITY ELSE 0 END) AS DYR_PROSES
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
	  <td rowspan="2">STOCK BULAN LALU<br>
	    <?php 
		  if($Thn2!="" and $Bln2!=""){
		  // Tampilkan hasil		  
		  echo $tanggal->format('F Y'); 
		  }
		?></td>
	  <td align="right">WARNA</td>
	  <td align="right"><?php echo number_format($rLalu['dyr_proses'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_stkmati'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_sample'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_jasa'],2);?></td>
	  <td align="right"><?php echo number_format($rLalu['dyr_proses']+$rLalu['dyr_stkmati']+$rLalu['dyr_sample']+$rLalu['dyr_jasa'],2);?></td>
	  </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rLalu['gyr_proses'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_stkmati'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_sample'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format($rLalu['gyr_proses']+$rLalu['gyr_stkmati']+$rLalu['gyr_sample']+$rLalu['gyr_jasa'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">PINDAHAN MASUK</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">SELISIH ERP</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">MASUK</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWARNA['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukSAMPLE['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Masuk['GYR_PROSES']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21Masuk['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">RETUR</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukWarnaRETUR['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_STKMATI']+$rowdb21MasukWarnaRETUR['DYR_SAMPLE']+$rowdb21MasukWarnaRETUR['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21MasukRETUR['GYR_PROSES']+$rowdb21MasukRETUR['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">KELUAR</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarSS['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarSS['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarSS['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Keluar['GYR_PROSES']+$rowdb21Keluar['GYR_STKMATI']+$rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarSS['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">RETUR</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['DYR_PROSES']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21KeluarRetur['GYR_PROSES']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">JUAL</td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['DYR_PROSES']+$rowdb21Jual['DYR_STKMATI']+$rowdb21Jual['DYR_SAMPLE']+$rowdb21Jual['DYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_PROSES'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_STKMATI'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_SAMPLE'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_JASA'],2);?></td>
	    <td align="right"><?php echo number_format($rowdb21Jual['GYR_PROSES']+$rowdb21Jual['GYR_STKMATI']+$rowdb21Jual['GYR_SAMPLE']+$rowdb21Jual['GYR_JASA'],2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">AKHIR<br>
	      <?php 
		  if($Thn2!="" and $Bln2!=""){	
		  // Tampilkan hasil
		  $tanggalskrng->format('Y-m-d'); // Output: 2025-03-31
		  echo $tanggalskrng->format('F Y');
		  } 		  	
		?></td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_proses']+$rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_PROSES'])-($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarRetur['DYR_PROSES']+$rowdb21Jual['DYR_PROSES']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_stkmati']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukRETUR['DYR_STKMATI'])-($rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21Jual['DYR_STKMATI']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['dyr_sample']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWarnaRETUR['DYR_SAMPLE'])-($rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21Jual['DYR_SAMPLE']),2);?></td>
	    <td align="right"><?php echo number_format($rJln['dyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format((($rLalu['dyr_proses']+$rLalu['dyr_stkmati']+$rLalu['dyr_sample']+$rLalu['dyr_jasa'])+($rowdb21MasukWARNA['DYR_PROSES']+$rowdb21MasukWarnaRETUR['DYR_PROSES']+$rowdb21MasukWARNA['DYR_STKMATI']+$rowdb21MasukWARNA['DYR_SAMPLE']+$rowdb21MasukWARNA['DYR_JASA'])+($rowdb21MasukRETUR['DYR_PROSES']+$rowdb21MasukRETUR['DYR_STKMATI']+$rowdb21MasukRETUR['DYR_SAMPLE']+$rowdb21MasukRETUR['DYR_JASA']))-(($rowdb21KeluarWarna['DYR_PROSES']+$rowdb21KeluarWarna['DYR_STKMATI']+$rowdb21KeluarWarna['DYR_SAMPLE']+$rowdb21KeluarSS['DYR_JASA'])+($rowdb21KeluarRetur['DYR_PROSES']+$rowdb21KeluarRetur['DYR_STKMATI']+$rowdb21KeluarRetur['DYR_SAMPLE']+$rowdb21KeluarRetur['DYR_JASA'])+($rowdb21Jual['DYR_PROSES']+$rowdb21Jual['DYR_STKMATI']+$rowdb21Jual['DYR_SAMPLE']+$rowdb21Jual['DYR_JASA'])),2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_proses']+$rowdb21Masuk['GYR_PROSES']+$rowdb21MasukRETUR['GYR_PROSES'])-($rowdb21Keluar['GYR_PROSES']+$rowdb21KeluarRetur['GYR_PROSES']+$rowdb21Jual['GYR_PROSES']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_stkmati']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_STKMATI'])-($rowdb21KeluarSS['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21Jual['GYR_STKMATI']),2);?></td>
	    <td align="right"><?php echo number_format(($rLalu['gyr_sample']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_SAMPLE'])-($rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21Jual['GYR_SAMPLE']),2);?></td>
	    <td align="right"><?php echo number_format($rJln['gyr_jasa'],2);?></td>
	    <td align="right"><?php echo number_format((($rLalu['gyr_proses']+$rLalu['gyr_stkmati']+$rLalu['gyr_sample']+$rLalu['gyr_jasa'])+($rowdb21Masuk['GYR_PROSES']+$rowdb21Masuk['GYR_STKMATI']+$rowdb21MasukSAMPLE['GYR_SAMPLE']+$rowdb21Masuk['GYR_JASA'])+($rowdb21MasukRETUR['GYR_PROSES']+$rowdb21MasukRETUR['GYR_STKMATI']+$rowdb21MasukRETUR['GYR_SAMPLE']+$rowdb21MasukRETUR['GYR_JASA']))-(($rowdb21Keluar['GYR_PROSES']+$rowdb21Keluar['GYR_STKMATI']+$rowdb21Keluar['GYR_SAMPLE']+$rowdb21KeluarSS['GYR_JASA'])+($rowdb21KeluarRetur['GYR_PROSES']+$rowdb21KeluarRetur['GYR_STKMATI']+$rowdb21KeluarRetur['GYR_SAMPLE']+$rowdb21KeluarRetur['GYR_JASA'])+($rowdb21Jual['GYR_PROSES']+$rowdb21Jual['GYR_STKMATI']+$rowdb21Jual['GYR_SAMPLE']+$rowdb21Jual['GYR_JASA'])),2);?></td>
	    </tr>
	  <tr>
	    <td rowspan="2">STOCK OPNAME<br>
		<?php 
		  if($Thn2!="" and $Bln2!=""){	
		  // Tampilkan hasil
		  $tanggalskrng->format('Y-m-d'); // Output: 2025-03-31
		  echo $tanggalskrng->format('F Y');} 
		?></td>
	    <td align="right">WARNA</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	  <tr>
	    <td align="right">GREY</td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    <td align="right"><?php echo number_format("0",2);?></td>
	    </tr>
	              </tbody>
       <tfoot>				
					</tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div> 
	</form>		
      </div><!-- /.container-fluid -->
    <!-- /.content -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>	
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
	$(function () {
		//Datepicker
    $('#datepicker').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker1').datetimepicker({
      format: 'YYYY-MM-DD'
    });
    $('#datepicker2').datetimepicker({
      format: 'YYYY-MM-DD'
    });
	
});		
	 function cetak_cek(filename) {
  var awal = document.querySelector('select[name="thn"]').value;
  var akhir = document.querySelector('select[name="bln"]').value;

  if (awal !== "" && akhir !== "") {
    let url = 'pages/cetak/' + filename + '.php?thn=' + awal + '&bln=' + akhir;
    window.open(url, '_blank');
  } else {
    alert('Filter tidak boleh kosong!');
  }
}
</script>
