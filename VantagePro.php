<?php

class VantagePro {

	private $OPTIONS=array();
	private $DATA=array();
	private $ErrorMessage="";
	private $WarningMessage=array();

	private $CalbackLOOP="";
	private $CalbackLOOP2="";

	private $crc_table=array(
		 0x0, 0x1021, 0x2042, 0x3063, 0x4084, 0x50a5, 0x60c6, 0x70e7,
		0x8108, 0x9129, 0xa14a, 0xb16b, 0xc18c, 0xd1ad, 0xe1ce, 0xf1ef,
		0x1231, 0x210, 0x3273, 0x2252, 0x52b5, 0x4294, 0x72f7, 0x62d6,
		0x9339, 0x8318, 0xb37b, 0xa35a, 0xd3bd, 0xc39c, 0xf3ff, 0xe3de,
		0x2462, 0x3443, 0x420, 0x1401, 0x64e6, 0x74c7, 0x44a4, 0x5485,
		0xa56a, 0xb54b, 0x8528, 0x9509, 0xe5ee, 0xf5cf, 0xc5ac, 0xd58d,
		0x3653, 0x2672, 0x1611, 0x630, 0x76d7, 0x66f6, 0x5695, 0x46b4,
		0xb75b, 0xa77a, 0x9719, 0x8738, 0xf7df, 0xe7fe, 0xd79d, 0xc7bc,
		0x48c4, 0x58e5, 0x6886, 0x78a7, 0x840, 0x1861, 0x2802, 0x3823,
		0xc9cc, 0xd9ed, 0xe98e, 0xf9af, 0x8948, 0x9969, 0xa90a, 0xb92b,
		0x5af5, 0x4ad4, 0x7ab7, 0x6a96, 0x1a71, 0xa50, 0x3a33, 0x2a12,
		0xdbfd, 0xcbdc, 0xfbbf, 0xeb9e, 0x9b79, 0x8b58, 0xbb3b, 0xab1a,
		0x6ca6, 0x7c87, 0x4ce4, 0x5cc5, 0x2c22, 0x3c03, 0xc60, 0x1c41,
		0xedae, 0xfd8f, 0xcdec, 0xddcd, 0xad2a, 0xbd0b, 0x8d68, 0x9d49,
		0x7e97, 0x6eb6, 0x5ed5, 0x4ef4, 0x3e13, 0x2e32, 0x1e51, 0xe70,
		0xff9f, 0xefbe, 0xdfdd, 0xcffc, 0xbf1b, 0xaf3a, 0x9f59, 0x8f78,
		0x9188, 0x81a9, 0xb1ca, 0xa1eb, 0xd10c, 0xc12d, 0xf14e, 0xe16f,
		0x1080, 0xa1, 0x30c2, 0x20e3, 0x5004, 0x4025, 0x7046, 0x6067,
		0x83b9, 0x9398, 0xa3fb, 0xb3da, 0xc33d, 0xd31c, 0xe37f, 0xf35e,
		0x2b1, 0x1290, 0x22f3, 0x32d2, 0x4235, 0x5214, 0x6277, 0x7256,
		0xb5ea, 0xa5cb, 0x95a8, 0x8589, 0xf56e, 0xe54f, 0xd52c, 0xc50d,
		0x34e2, 0x24c3, 0x14a0, 0x481, 0x7466, 0x6447, 0x5424, 0x4405,
		0xa7db, 0xb7fa, 0x8799, 0x97b8, 0xe75f, 0xf77e, 0xc71d, 0xd73c,
		0x26d3, 0x36f2, 0x691, 0x16b0, 0x6657, 0x7676, 0x4615, 0x5634,
		0xd94c, 0xc96d, 0xf90e, 0xe92f, 0x99c8, 0x89e9, 0xb98a, 0xa9ab,
		0x5844, 0x4865, 0x7806, 0x6827, 0x18c0, 0x8e1, 0x3882, 0x28a3,
		0xcb7d, 0xdb5c, 0xeb3f, 0xfb1e, 0x8bf9, 0x9bd8, 0xabbb, 0xbb9a,
		0x4a75, 0x5a54, 0x6a37, 0x7a16, 0xaf1, 0x1ad0, 0x2ab3, 0x3a92,
		0xfd2e, 0xed0f, 0xdd6c, 0xcd4d, 0xbdaa, 0xad8b, 0x9de8, 0x8dc9,
		0x7c26, 0x6c07, 0x5c64, 0x4c45, 0x3ca2, 0x2c83, 0x1ce0, 0xcc1,
		0xef1f, 0xff3e, 0xcf5d, 0xdf7c, 0xaf9b, 0xbfba, 0x8fd9, 0x9ff8,
		0x6e17, 0x7e36, 0x4e55, 0x5e74, 0x2e93, 0x3eb2, 0xed1, 0x1ef0,
		);

	function __construct($ip,$port) {
		$this->OPTIONS["ip"]=$ip;
		$this->OPTIONS["port"]=$port;
		}

	function SetOption($key,$value) {
		$this->OPTIONS[$key]=$value;
		}

	function GetLastErrorMessage() {
		return $this->ErrorMessage;
		}

	function GetWarningMessages() {
		return $this->WarningMessage;
		}

	function GetData() {
		return $this->DATA;
		}


	function SetCallback($callback,$packet_type) {
		if ($packet_type=="LOOP" && is_callable($callback)) {$this->CalbackLOOP=$callback;return true;}
		if ($packet_type=="LOOP2" && is_callable($callback)) {$this->CalbackLOOP2=$callback;return true;}
		return false;
		}

	function temperatureRound($temp) {
		return sprintf("%.3f", $temp);
		}


	function ConvertLOOPData($ret) {


$fw=fopen(dirname(__FILE__)."/LOOP.txt","a");
if ($fw) {
	fwrite($fw,date("Y-m-d H:i:s")."\t".json_encode($ret)."\n");
	fclose($fw);
	}


		// Current Barometer. Units are (in Hg / 1000). The barometric
		// value should be between 20 inches and 32.5 inches in Vantage
		// Pro and between 20 inches and 32.5 inches in both Vantatge Pro
		// Vantage Pro2. Values outside these ranges will not be logged.

		$ret["Barometer"]=(($ret["Barometer"]*25.399999705)/1000)/0.75; // ��������� � ��������
		switch ($ret["BarTrend"]) {
			case 196: $ret["BarTrend"]="--";break;
			case 236: $ret["BarTrend"]="-";break;
			case 20: $ret["BarTrend"]="+";break;
			case 60: $ret["BarTrend"]="++";break;
			case 0: $ret["BarTrend"]="~";
			default: $ret["BarTrend"]=null;
			}

		// The value is sent as 10th of a degree in F. For example, 795 is
		// returned for 79.5�F.

		$ret["InsideTemperature"]=$this->temperatureRound((($ret["InsideTemperature"]/10)-32)/1.8); // ��������� � �������� �������
		$ret["OutsideTemperature"]=$this->temperatureRound((($ret["OutsideTemperature"]/10)-32)/1.8); // ��������� � �������� �������

		// This is the relative humidity in %, such as 50 is returned for 50%
		$ret["InsideHumidity"]=$ret["InsideHumidity"]; // �� ���� ���-�� � ���������� ��������

		// It is a byte unsigned value in mph. If the wind speed is dashed
		// because it lost synchronization with the radio or due to some
		// other reason, the wind speed is forced to be 0.
		if ($ret["WindSpeed"]==255) $ret["WindSpeed"]=null;
		else $ret["WindSpeed"]=$this->temperatureRound($ret["WindSpeed"] * 0.44704); // ��������� � �/�

		if ($ret["WindSpeed10MinutesAvg"]==255) $ret["WindSpeed10MinutesAvg"]=null;
		else $ret["WindSpeed10MinutesAvg"]=$this->temperatureRound($ret["WindSpeed10MinutesAvg"] * 0.44704); // ��������� � �/�

		// It is a two byte unsigned value from 1 to 360 degrees. (0� is no
		// wind data, 90� is East, 180� is South, 270� is West and 360� is
		// north)
		if ($ret["WindDirection"]==0) $ret["WindDirection"]=null;

		// This field supports seven extra temperature stations.
		// Each byte is one extra temperature value in whole degrees F with
		// an offset of 90 degrees. For example, a value of 0 = -90�F ; a
		// value of 100 = 10�F ; and a value of 169 = 79�F.

		// This field supports four soil temperature sensors, in the same
		// format as the Extra Temperature field above

		// This field supports four leaf temperature sensors, in the same
		// format as the Extra Temperature field above

		// This is the relative humitiy in %.

		// Relative humidity in % for extra seven humidity stations. 

		// This value is sent as number of rain clicks (0.2mm or 0.01in).
		// For example, 256 can represent 2.56 inches/hour.
		$ret["RainRate"]=0.2*$ret["RainRate"]; // ��������� � ��

		// The storm is stored as 100th of an inch
		$ret["StormRain"]=($ret["StormRain"]/100)/0.0394; // ��������� � ��

		$ret["DayRain"]=0.2*$ret["DayRain"]; // ��������� � ��
		$ret["MonthRain"]=0.2*$ret["MonthRain"]; // ��������� � ��
		$ret["YearRain"]=0.2*$ret["YearRain"]; // ��������� � ��

		// Bit 15 to bit 12 is the month, bit 11 to bit 7 is the day and bit 6 to
		// bit 0 is the year offseted by 2000.
		if ($ret["StartDateofcurrentStorm"]==65535) $ret["StartDateofcurrentStorm"]=null;
		else $ret["StartDateofcurrentStorm"]=(($ret["StartDateofcurrentStorm"]&0x3F)+2000)."-".sprintf("%02d", (($ret["StartDateofcurrentStorm"]>>12)&0x1F) )."-".sprintf("%02d", ((($ret["StartDateofcurrentStorm"]>>7)&0x0F)) );

		$ret["DayET"]=$this->temperatureRound(($ret["DayET"]/1000)/0.0394); // ��������� � �� 0.3
		$ret["MonthET"]=$this->temperatureRound(($ret["MonthET"]/100)/0.0394); // ��������� � ��  7.4
		$ret["YearET"]=$this->temperatureRound(($ret["YearET"]/100)/0.0394); // ��������� � ��

		if ($ret["SolarRadiation"]<0 || $ret["SolarRadiation"]==65535) $ret["SolarRadiation"]=null;

		$ret["TimeofSunrise"]=substr($ret["TimeofSunrise"],0,2).":".substr($ret["TimeofSunrise"],2,2);
		$ret["TimeofSunset"]=substr($ret["TimeofSunset"],0,2).":".substr($ret["TimeofSunset"],2,2);

		// Undocumented ?
		$ret["UV"]=$ret["UV"]/10;

		// Voltage = ((Data * 300)/512)/100.0
		$ret["ConsoleBatteryVoltage"]=(($ret["ConsoleBatteryVoltage"] * 300)/512)/100;

		return $ret;
		}

	function ConvertLOOP2Data($ret) {

$fw=fopen(dirname(__FILE__)."/LOOP2.txt","a");
if ($fw) {
	fwrite($fw,date("Y-m-d H:i:s")."\t".json_encode($ret)."\n");
	fclose($fw);
	}


		// Current Barometer. Units are (in Hg / 1000). The barometric
		// value should be between 20 inches and 32.5 inches in Vantage
		// Pro and between 20 inches and 32.5 inches in both Vantatge Pro
		// Vantage Pro2. Values outside these ranges will not be logged.

		$ret["Barometer"]=(($ret["Barometer"]*25.399999705)/1000)/0.75; // ��������� � ��������
		switch ($ret["BarTrend"]) {
			case 196: $ret["BarTrend"]="++";break;
			case 236: $ret["BarTrend"]="+";break;
			case 20: $ret["BarTrend"]="-";break;
			case 60: $ret["BarTrend"]="--";break;
			case 0: $ret["BarTrend"]="~";
			default: $ret["BarTrend"]=null;
			}

		// The value is sent as 10th of a degree in F. For example, 795 is
		// returned for 79.5�F.

		$ret["InsideTemperature"]=$this->temperatureRound( (($ret["InsideTemperature"]/10)-32)/1.8 ); // ��������� � �������� �������
		$ret["OutsideTemperature"]=$this->temperatureRound( (($ret["OutsideTemperature"]/10)-32)/1.8 ); // ��������� � �������� �������
    
		// This is the relative humidity in %, such as 50 is returned for 50%
		$ret["InsideHumidity"]=$ret["InsideHumidity"]; // �� ���� ���-�� � ���������� ��������
    
		// It is a byte unsigned value in mph. If the wind speed is dashed
		// because it lost synchronization with the radio or due to some
		// other reason, the wind speed is forced to be 0.
		$ret["WindSpeed"]=$ret["WindSpeed"] * 0.44704; // ��������� � �/�
		$ret["WindSpeed10MinutesAvg"]=$ret["WindSpeed10MinutesAvg"] * 0.044704; // ��������� � �/�
		$ret["WindSpeed2MinutesAvg"]=$ret["WindSpeed2MinutesAvg"] * 0.044704; // ��������� � �/�
		$ret["WindGust10MinutesAvg"]=$ret["WindGust10MinutesAvg"] * 0.44704; // ��������� � �/�
    
		// It is a two byte unsigned value from 1 to 360 degrees. (0� is no
		// wind data, 90� is East, 180� is South, 270� is West and 360� is
		// north)
		if ($ret["WindDirection"]==0) $ret["WindDirection"]=null;
		if ($ret["WindGust10MinutesDirectionAvg"]==0) $ret["WindGust10MinutesDirectionAvg"]=null;
    
		// The value is a signed two byte value in whole degrees F.
		// 255 = dashed data
		if ($ret["DewPoint"]!=255) $ret["DewPoint"]=$this->temperatureRound(($ret["DewPoint"]-32)/1.8); else $ret["DewPoint"]=null; // ��������� � �������� �������

		// The value is a signed two byte value in whole degrees F.
		// 255 = dashed data
		if ($ret["HeatIndex"]==255) $ret["HeatIndex"]=null; else $ret["HeatIndex"]=$this->temperatureRound(($ret["HeatIndex"]-32)/1.8);

		// The value is a signed two byte value in whole degrees F.
		// 255 = dashed data
		if ($ret["WindChill"]==255) $ret["WindChill"]=null; else $ret["WindChill"]=$this->temperatureRound(($ret["WindChill"]-32)/1.8);

		// The value is a signed two byte value in whole degrees F.
		// 255 = dashed data
		if ($ret["THSWIndex"]==255) $ret["THSWIndex"]=null; else $ret["THSWIndex"]=$this->temperatureRound(($ret["THSWIndex"]-32)/1.8);
    
		// This value is sent as number of rain clicks (0.2mm or 0.01in).
		// For example, 256 can represent 2.56 inches/hour.
		$ret["RainRate"]=0.2*$ret["RainRate"]; // ��������� � ��
    
		// The storm is stored as 100th of an inch
		$ret["StormRain"]=($ret["StormRain"]/100)/0.0394; // ��������� � ��
    
		$ret["DailyRain"]=0.2*$ret["DailyRain"]; // ��������� � ��
		$ret["Last15minRain"]=0.2*$ret["Last15minRain"]; // ��������� � ��
		$ret["Last24HourRain"]=0.2*$ret["Last24HourRain"]; // ��������� � ��
    
		// Bit 15 to bit 12 is the month, bit 11 to bit 7 is the day and bit 6 to
		// bit 0 is the year offseted by 2000.
    
		if ($ret["StartDateofcurrentStorm"]==65535) $ret["StartDateofcurrentStorm"]=null;
		else $ret["StartDateofcurrentStorm"]=(($ret["StartDateofcurrentStorm"]&0x3F)+2000)."-".sprintf("%02d", (($ret["StartDateofcurrentStorm"]>>12)&0x1F) )."-".sprintf("%02d", ((($ret["StartDateofcurrentStorm"]>>7)&0x0F)) );
    
		$ret["DailyET"]=($ret["DailyET"]/1000)/0.0394; // ��������� � �� 0.3

		if ($ret["SolarRadiation"]<0 || $ret["SolarRadiation"]==65535) $ret["SolarRadiation"]=null;
    
		if ($ret["UserEnteredBarometricOffset"]!=-1) $ret["UserEnteredBarometricOffset"]=($ret["UserEnteredBarometricOffset"]/1000)/0.0394; // ��������� � �� 0.3
		if ($ret["BarometricCalibrationNumber"]!=-1) $ret["BarometricCalibrationNumber"]=($ret["BarometricCalibrationNumber"]/1000)/0.0394; // ��������� � �� 0.3
		if ($ret["AbsoluteBarometricPressure"]!=-1) $ret["AbsoluteBarometricPressure"]=($ret["AbsoluteBarometricPressure"]/1000)/0.0394; // ��������� � �� 0.3
		if ($ret["AltimeterSetting"]!=-1) $ret["AltimeterSetting"]=($ret["AltimeterSetting"]/1000)/0.0394; // ��������� � �� 0.3

		// Undocumented ?
		$ret["UV"]=$ret["UV"]/10;

		return $ret;
		}

	function CheckCRC($packet) {
		$crc=0;
		for ($i=0;$i<strlen($packet);$i++) {
			$crc = ( $this->crc_table [ ($crc >> 8) ^ ord($packet[$i]) ] ^ (($crc << 8) & 0xFFFF) ) & 0xFFFF;
			}
		if ($crc === 0) return true; else return false;
		}

	function GetPacketType($packet) {

		$str ="Ch1/Ch2/Ch3/CBarTrend/CPacketType";
		$ret = unpack($str, $packet);
		
		if ($ret["PacketType"]==0) return "LOOP";
		if ($ret["PacketType"]==1) return "LOOP2";
		return "UNKNOWN";
		}

	function PacketLOOPToData($p) {

		if (strlen($p)!=99) return false;
		if (!$this->CheckCRC($p)) return false;

		$str ="Ch1/Ch2/Ch3/CBarTrend/CPacketType/vNextRecord/";
		$str.="vBarometer/vInsideTemperature/CInsideHumidity/vOutsideTemperature/CWindSpeed/CWindSpeed10MinutesAvg/vWindDirection/";
		$str.="CExtraTemp1/CExtraTemp2/CExtraTemp3/CExtraTemp4/CExtraTemp5/CExtraTemp6/CExtraTemp7/";
		$str.="CSoilTemp1/CSoilTemp2/CSoilTemp3/CSoilTemp4/";
		$str.="CLeafTemp1/CLeafTemp2/CLeafTemp3/CLeafTemp4/";
		$str.="COutsideHumidity/";
		$str.="CExtraHumidity1/CExtraHumidity2/CExtraHumidity3/CExtraHumidity4/CExtraHumidity5/CExtraHumidity6/CExtraHumidity7/";
		$str.="vRainRate/CUV/vSolarRadiation/vStormRain/vStartDateofcurrentStorm/vDayRain/vMonthRain/vYearRain/vDayET/vMonthET/vYearET/";
		$str.="VSoilMoistures/VLeafWetnesses/";
		$str.="CInsideAlarms/CRainAlarms/C2OutsideAlarm/C8HumAlarm/C4SoilAndLeafAlarm/CTransmitterBatteryStatus/vConsoleBatteryVoltage/CForecastIcons/CForecastRulenumber/";
		$str.="vTimeofSunrise/vTimeofSunset/CLF/CCR/vCRC";

		$ret = unpack($str, $p);

		return $ret;
		}

	function PacketLOOP2ToData($p) {
		$str ="Ch1/Ch2/Ch3/CBarTrend/CPacketType/vUnused1/";
		$str.="vBarometer/vInsideTemperature/CInsideHumidity/vOutsideTemperature/CWindSpeed/CUnused2/vWindDirection/";
		$str.="vWindSpeed10MinutesAvg/vWindSpeed2MinutesAvg/vWindGust10MinutesAvg/vWindGust10MinutesDirectionAvg/sUnused3/sUnused4/";
		$str.="sDewPoint/CUnused5/COutsideHumidity/CUnused6/sHeatIndex/sWindChill/sTHSWIndex/vRainRate/cUV/vSolarRadiation/vStormRain/vStartDateofcurrentStorm/";
		$str.="vDailyRain/vLast15minRain/vLastHourRain/vDailyET/vLast24HourRain/CBarometricReductionMethod/sUserEnteredBarometricOffset/sBarometricCalibrationNumber/sBarometricSensorRawReading/sAbsoluteBarometricPressure/sAltimeterSetting/CUnused7/CUnused8/";
		$str.="CNext10minWindSpeedGraphPointer/CNext15minWindSpeedGraphPointer/CNextHourlyWindSpeedGraphPointer/CNextDailyWindSpeedGraphPointer/CNextMinuteRainGraphPointer/CNextRainStormGraphPointer/CIndextotheMinutewithinanHour/CNextMonthlyRain/CNextYearlyRain/CNextSeasonalRain/";
		$str.="v6UnusedBlock/CLF/CCR/vCRC";

		$ret = unpack($str, $p);
		return $ret;
		}

	function SaveData($packet_type,$data) {
		$d=array(
			"datetime"=>date("Y-m-d H:i:s"),
			"packet_type"=>$packet_type,
			"data"=>$data,
			);

		$this->DATA[]=$d;
		}

	function FetchData($packets_count) {

		$attemps=0;
		do {

			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if ($socket === false) {
			    $this->ErrorMessage="socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
				return false;
				}

			$result = socket_connect($socket, $this->OPTIONS["ip"], $this->OPTIONS["port"]);
			if ($result === false) {
			    $this->ErrorMessage="socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
				return false;
				}

			socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 2, 'usec' => 500000));

			$in="\n";
			socket_write($socket, $in, strlen($in));

			while ($out = socket_read($socket, 2048)) {
				if (urlencode($out)=="%0A%0D") break;
				}
			if (urlencode($out)=="%0A%0D") break;

			$attemps++;
			socket_close($socket);
			}
		while ($attemps<3);

		if ($attemps>=3) {
			$this->ErrorMessage="Can't initialize connection. Attemps count: ".$attemps;
			return false;
			};

		if ($packets_count<1) {
			$this->ErrorMessage="Wrong packets count requested. Minimum value is 2.";
			return false;
			}

		$in="LPS 3 ".$packets_count."\n";
		socket_write($socket, $in, strlen($in));

		$out = socket_read($socket, 1);
		$ack = unpack("Cack",$out);

		if ($ack["ack"]==0x06 || $ack["ack"]==0x0A) {
			}
		else {
			socket_close($socket);
			$this->ErrorMessage="Wrong answer. ACK code expected, but 0x".sprintf("%02x",$ack["ack"])." received (0x06).";
			return false;
			}
			                                    	
		$out="";
		while ($d = socket_read($socket, 65536)) {
			$out.=$d;

			$length=strlen($out);
			if ($length<99) continue;

			do {
				$length=strlen($out);

				if ($length!=0) {
					if ($length>=6) $pt=$this->GetPacketType($out);
    
					switch ($pt) {
						case "LOOP":
							if ($length<99) {
								socket_close($socket);
								$this->ErrorMessage="Wrong LOOP packet length: ".$length.". Expected 99.";
								return false;
								}
							$d=$this->PacketLOOPToData(substr($out,0,99));
							if ($d) {
								$d=$this->ConvertLOOPData($d);
								$this->SaveData("LOOP",$d);
								if (!empty($this->CalbackLOOP) && is_callable($this->CalbackLOOP)) call_user_func($this->CalbackLOOP,$d);
								}
							else $this->WarningMessage[]="CRC error at LOOP packet.";
							$out=substr($out,99);
		
							break;
						case "LOOP2":
							if ($length<99) {
								socket_close($socket);
								$this->ErrorMessage="Wrong LOOP2 packet length: ".$length.". Expected 99.";
								return false;
								}
							$d=$this->PacketLOOP2ToData(substr($out,0,99));
							if ($d) {
								$d=$this->ConvertLOOP2Data($d);
								$this->SaveData("LOOP2",$d);
								if (!empty($this->CalbackLOOP2) && is_callable($this->CalbackLOOP2)) call_user_func($this->CalbackLOOP2,$d);
								}
							else $this->WarningMessage="CRC error at LOOP2 packet.";
							$out=substr($out,99);

							break;
						default:
						}
					}
	
				} while ($length>0);

			$out="";
			}

		socket_close($socket);
		return true;
		}

	}

?>