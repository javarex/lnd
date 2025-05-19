<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MunicipalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('municipals')->insert($this->data());
    }

    public function data()
    {
        return array(
            array(
                "id" => 1251,
                "psgcCode" => "112301000",
                "citymunDesc" => "ASUNCION (SAUG)",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112301",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1252,
                "psgcCode" => "112303000",
                "citymunDesc" => "CARMEN",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112303",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1253,
                "psgcCode" => "112305000",
                "citymunDesc" => "KAPALONG",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112305",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1254,
                "psgcCode" => "112314000",
                "citymunDesc" => "NEW CORELLA",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112314",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1255,
                "psgcCode" => "112315000",
                "citymunDesc" => "CITY OF PANABO",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112315",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1256,
                "psgcCode" => "112317000",
                "citymunDesc" => "ISLAND GARDEN CITY OF SAMAL",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112317",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1257,
                "psgcCode" => "112318000",
                "citymunDesc" => "SANTO TOMAS",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112318",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1258,
                "psgcCode" => "112319000",
                "citymunDesc" => "CITY OF TAGUM (Capital)",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112319",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1259,
                "psgcCode" => "112322000",
                "citymunDesc" => "TALAINGOD",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112322",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1260,
                "psgcCode" => "112323000",
                "citymunDesc" => "BRAULIO E. DUJALI",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112323",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1261,
                "psgcCode" => "112324000",
                "citymunDesc" => "SAN ISIDRO",
                "regDesc" => "11",
                "provCode" => "1123",
                "citymunCode" => "112324",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1262,
                "psgcCode" => "112401000",
                "citymunDesc" => "BANSALAN",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112401",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1263,
                "psgcCode" => "112402000",
                "citymunDesc" => "DAVAO CITY",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112402",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1264,
                "psgcCode" => "112403000",
                "citymunDesc" => "CITY OF DIGOS (Capital)",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112403",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1265,
                "psgcCode" => "112404000",
                "citymunDesc" => "HAGONOY",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112404",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1266,
                "psgcCode" => "112406000",
                "citymunDesc" => "KIBLAWAN",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112406",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1267,
                "psgcCode" => "112407000",
                "citymunDesc" => "MAGSAYSAY",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112407",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1268,
                "psgcCode" => "112408000",
                "citymunDesc" => "MALALAG",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112408",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1269,
                "psgcCode" => "112410000",
                "citymunDesc" => "MATANAO",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112410",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1270,
                "psgcCode" => "112411000",
                "citymunDesc" => "PADADA",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112411",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1271,
                "psgcCode" => "112412000",
                "citymunDesc" => "SANTA CRUZ",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112412",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1272,
                "psgcCode" => "112414000",
                "citymunDesc" => "SULOP",
                "regDesc" => "11",
                "provCode" => "1124",
                "citymunCode" => "112414",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1273,
                "psgcCode" => "112501000",
                "citymunDesc" => "BAGANGA",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112501",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1274,
                "psgcCode" => "112502000",
                "citymunDesc" => "BANAYBANAY",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112502",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1275,
                "psgcCode" => "112503000",
                "citymunDesc" => "BOSTON",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112503",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1276,
                "psgcCode" => "112504000",
                "citymunDesc" => "CARAGA",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112504",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1277,
                "psgcCode" => "112505000",
                "citymunDesc" => "CATEEL",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112505",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1278,
                "psgcCode" => "112506000",
                "citymunDesc" => "GOVERNOR GENEROSO",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112506",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1279,
                "psgcCode" => "112507000",
                "citymunDesc" => "LUPON",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112507",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1280,
                "psgcCode" => "112508000",
                "citymunDesc" => "MANAY",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112508",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1281,
                "psgcCode" => "112509000",
                "citymunDesc" => "CITY OF MATI (Capital)",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112509",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1282,
                "psgcCode" => "112510000",
                "citymunDesc" => "SAN ISIDRO",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112510",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1283,
                "psgcCode" => "112511000",
                "citymunDesc" => "TARRAGONA",
                "regDesc" => "11",
                "provCode" => "1125",
                "citymunCode" => "112511",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1284,
                "psgcCode" => "118201000",
                "citymunDesc" => "COMPOSTELA",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118201",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1285,
                "psgcCode" => "118202000",
                "citymunDesc" => "LAAK (SAN VICENTE)",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118202",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1286,
                "psgcCode" => "118203000",
                "citymunDesc" => "MABINI (DOÑA ALICIA)",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118203",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1287,
                "psgcCode" => "118204000",
                "citymunDesc" => "MACO",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118204",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1288,
                "psgcCode" => "118205000",
                "citymunDesc" => "MARAGUSAN (SAN MARIANO)",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118205",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1289,
                "psgcCode" => "118206000",
                "citymunDesc" => "MAWAB",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118206",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1290,
                "psgcCode" => "118207000",
                "citymunDesc" => "MONKAYO",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118207",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1291,
                "psgcCode" => "118208000",
                "citymunDesc" => "MONTEVISTA",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118208",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1292,
                "psgcCode" => "118209000",
                "citymunDesc" => "NABUNTURAN (Capital)",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118209",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1293,
                "psgcCode" => "118210000",
                "citymunDesc" => "NEW BATAAN",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118210",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1294,
                "psgcCode" => "118211000",
                "citymunDesc" => "PANTUKAN",
                "regDesc" => "11",
                "provCode" => "1182",
                "citymunCode" => "118211",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1295,
                "psgcCode" => "118601000",
                "citymunDesc" => "DON MARCELINO",
                "regDesc" => "11",
                "provCode" => "1186",
                "citymunCode" => "118601",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1296,
                "psgcCode" => "118602000",
                "citymunDesc" => "JOSE ABAD SANTOS (TRINIDAD)",
                "regDesc" => "11",
                "provCode" => "1186",
                "citymunCode" => "118602",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1297,
                "psgcCode" => "118603000",
                "citymunDesc" => "MALITA",
                "regDesc" => "11",
                "provCode" => "1186",
                "citymunCode" => "118603",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1298,
                "psgcCode" => "118604000",
                "citymunDesc" => "SANTA MARIA",
                "regDesc" => "11",
                "provCode" => "1186",
                "citymunCode" => "118604",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1299,
                "psgcCode" => "118605000",
                "citymunDesc" => "SARANGANI",
                "regDesc" => "11",
                "provCode" => "1186",
                "citymunCode" => "118605",
                "created_at" => null,
                "updated_at" => null
            ),
            array(
                "id" => 1648,
                "psgcCode" => "0",
                "citymunDesc" => "No municipal",
                "regDesc" => "11",
                "provCode" => "0",
                "citymunCode" => "0",
                "created_at" => null,
                "updated_at" => null
            )
        );

    }
}
