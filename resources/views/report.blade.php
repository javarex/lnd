<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate of Participation</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        *{
            margin: 0;
            padding: 0;
        }
        .certificate-container {
            border: 1px solid #cbd5e1; /* Tailwind's gray-400 */
            padding: 2.5rem;
            /*text-align: center;*/
            /*box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);*/
            /*overflow: hidden;*/
            margin: auto;
        }
        .signatory {
            margin-top: 4rem;
            margin-bottom: 0.5rem;
        }

        .signatory p:first-child {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .signatory p:last-child {
            font-size: 1rem;
        }

        hr {
            border-top: 1px solid #d1d5db; /* Tailwind's gray-300 */
            margin: 1rem auto;
            width: 91.666667%;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            /*align-items: flex-start;*/
            font-size: 10px;
            padding: 1.5rem 2.5rem 0 2.5rem;
        }

        .footer .left {
            /*display: inline;*/
            align-items: flex-start;
        }

        .footer .left img {
            height: 4rem;
            margin-right: 0.5rem;
        }

        .footer .right {
            /*display: inline;*/
            align-items: flex-start;
        }
        .footer .right table {
            border-collapse: collapse;
            border: 1px solid #cbd5e1;
        }

        .footer .right td {
            border: 1px solid #cbd5e1;
            padding: 0.25rem 0.5rem;
        }

        .footer .right td:first-child,
        .footer .right td:nth-child(3) {
            font-weight: 600;
        }
        .text-lg {
            font-size: 1.125rem /* 18px */;
            line-height: 1.75rem /* 28px */;
        }
        .mb-4 {
            margin-bottom: 1rem /* 16px */;
        }

        .school {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .description {
            text-align: center;
            padding: 0 5rem;
            font-size: 12pt;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .participant-name {
            font-size: 2.6rem;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 0.25rem;
        }

        .certificate-title {
            font-size: 1.875rem; /* text-3xl */
            font-style: italic;
            font-weight: bold;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            font-family: 'Old English Text MT', serif;
        }
        .font-bold {
            font-weight: 700;
        }
        .uppercase {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div>
    @foreach ($users as $item)
        <div class="certificate-container">
            <div>
                <div style="text-align: center;">
                    <img src="images/DepEd.png" alt="DepEd Logo" width="100">
                    <p class="text-gray uppercase">Republic of the Philippines</p>
                    <p class="text-gray">Department of Education</p>
                    <p class="text-gray">Region XI</p>
                    <p class="text-gray">Schools Division of Davao de Oro</p>
                </div>

                <div style="text-align: center;">
                    <h1 class="certificate-title">Certificate of Participation</h1>
                    <p class="mb-4 text-lg">is given to</p>
                    <h2 class="participant-name">{{ $item['name'] }}</h2>
                    <p class="school">Golden Valley NHS</p>
                    <p class="description">
                        for her active participation during the conduct of
                        <span class="font-bold uppercase">{{$items->training?->training_name}}</span>
                        held at The Ritz Hotel at Garden Oases, Porras St., Bo. Obrero, Davao City on December 1–6, 2023.
                    </p>
                    <p class="date">
                        Given this <strong>6<sup>th</sup> day of December 2023</strong> at The Ritz Hotel at Garden Oases, Davao City.
                    </p>

                    <div class="signatory">
                        <p>CRISTY C. EPE</p>
                        <p>Schools Division Superintendent</p>
                    </div>
                </div>



                <hr>

                <div class="footer">
                    <div class="left" style="display: inline-block">
                        <img src="images/deped_division.jpeg" alt="DepEd Logo" style="display: inline-block">
                        <div style="display: inline-block">
                            <p><strong>Address:</strong> Capitol Complex, Brgy. Cabidianan, Nabunturan, Davao de Oro</p>
                            <p><strong>Contact No.:</strong> 0951-387-1728 (TNT); 0999-935-5399 (Smart)</p>
                            <p><strong>Email Address:</strong> davaodeoro@deped.gov.ph</p>
                            <p><strong>Website:</strong> www.deped-ddo.com</p>
                        </div>
                    </div>
                    <div class="right" style="display: inline-block; margin-left: 30%">
                        <table>
                            <tr>
                                <td>Doc. Ref. Code</td>
                                <td>PAWIM-F-44</td>
                                <td>Rev</td>
                                <td>00</td>
                            </tr>
                            <tr>
                                <td>Effectivity</td>
                                <td>09.12.22</td>
                                <td>Page</td>
                                <td>1 of 1</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div style="page-break-after: always;"></div>
    @endforeach
</div>
</body>
</html>
