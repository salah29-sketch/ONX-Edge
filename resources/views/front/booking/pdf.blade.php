<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Bon de reservation - ONX</title>
    <style>
        /* إعدادات هوامش الصفحة لمنع توليد صفحة ثانية بيضاء */
        @page { 
            size: A4 portrait; 
            margin: 55px 18px 20px; 
        }
        html, body { 
            margin: 0; 
            padding: 0; 
            background: #fff; 
            -webkit-print-color-adjust: exact;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: ltr; color: #111; font-size: 10px;
            line-height: 1.35;
        }
        /* جعل الحاوية مرنة الارتفاع ومرتبطة بحدود الصفحة */
        .page { 
            width: 86%; 
            margin: 0 auto; padding-top: 0; 
            position: relative;
            box-sizing: border-box;
        }
        .header { border: none; border-bottom: 1.6px solid #1f1f1f; padding: 12px 14px 11px; margin-bottom: 14px; background: #fff; }
        .header-table { width: 100%; border-collapse: collapse; }
        .header-table td { border: none; padding: 0; vertical-align: middle; }
        .logo-cell { width: 68px; }
        .logo-wrap { width: 58px; height: 58px; }
        .logo-wrap img { width: 58px; height: auto; display: block; }
        .brand-cell { padding-left: 10px; }
        .brand { margin: 0; font-size: 18px; font-weight: bold; color: #111; letter-spacing: .5px; }
        .brand-sub { margin: 3px 0 0; font-size: 8px; color: #595959; }
        .doc-cell { text-align: right; }
        .doc-title { margin: 0; font-size: 18px; font-weight: bold; color: #111; }
        .doc-ref { margin-top: 4px; font-size: 8px; color: #6a6a6a; }
        .notice { margin-top: auto; padding: 7px 9px; border-left: 4px solid #d65f13; background: #fff5ee; color: #222; font-size: 8px; }
        .section { margin-bottom: 12px; }
        .section-title { margin: 0 0 7px; font-size: 12px; font-weight: bold; color: #111; padding-bottom: 4px; border-bottom: 1px solid #d65f13; }
        .two-col { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .two-col td { width: 50%; border: none; padding: 0; vertical-align: top; }
        .box { padding: 2px 0 0; min-height: 126px; background: #fff; }
        .box-title { margin: 0 0 8px; font-size: 10.5px; font-weight: bold; color: #111; padding-bottom: 4px; border-bottom: 1px solid #ededed; }
        .item { margin-bottom: 7px; }
        .item:last-child { margin-bottom: 0; }
        .label { display: block; font-size: 7.4px; color: #6a6a6a; margin-bottom: 2px; text-transform: uppercase; letter-spacing: .35px; }
        .value { display: block; font-size: 10px; font-weight: bold; color: #161616; word-break: break-word; }
        .money-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
        .money-table td { width: 33.33%; border: none; padding: 0; vertical-align: top; }
        .money-box { border: 1.3px solid #262626; background: #fff; text-align: center; padding: 10px 8px; min-height: 64px; }
        .money-box.total { background: #fff6ef; border-color: #d65f13; }
        .money-box.paid  { background: #f0fdf4; border-color: #16a34a; }
        .money-box.remaining { background: #fef2f2; border-color: #dc2626; }
        .money-label { display: block; font-size: 7.3px; color: #5f5f5f; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .4px; }
        .money-value { font-size: 12px; font-weight: bold; color: #111; line-height: 1.2; }
        .money-box.total .money-value { color: #c9540d; }
        .money-box.paid  .money-value { color: #16a34a; }
        .money-box.remaining .money-value { color: #dc2626; }
        .money-sub { display: block; margin-top: 4px; font-size: 7px; color: #7b7b7b; }
        .features-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .features-table td { border: 1px solid #e5e7eb; padding: 5px 8px; font-size: 9px; color: #374151; }
        .features-table tr:nth-child(even) td { background: #f9fafb; }
        .notes-box { background: #fff; padding: 2px 0 0; min-height: 20px; font-size: 9px; color: #374151; }
        .terms { background: #fff; padding: 2px 0 0; }
        .terms ul { margin: 0; padding-left: 15px; }
        .terms li { margin: 0 0 4px; font-size: 8px; color: #151515; }
        
        /* ضبط الحاوية لتثبت بالأسفل تماماً وبشكل متناسق مع قياس الصفحة */
        .bottom-container {
            position: absolute;
            bottom: 15px; /* ترفع التواقيع والكتابة عن الحافة السفلية للـ A4 دون الخروج عنها */
            left: 0;
            width: 100%;
        }
        .sign-table { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin-top: 0; }
        .sign-table td { width: 50%; border: none; padding: 0; vertical-align: top; }
        .sign-box { border: 1px solid #d1d5db; padding: 10px; min-height: 66px; background: #fff; }
        .sign-title { font-size: 10px; font-weight: bold; margin-bottom: 22px; color: #111; }
        .sign-line { border-top: 1px solid #d1d5db; padding-top: 4px; font-size: 7.4px; color: #666; }
        .footer { margin-top: 14px; padding-top: 6px; border-top: 1px solid #d65f13; font-size: 7.5px; color: #4d4d4d; }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-table td { border: none; padding: 0; vertical-align: top; }
        .text-right { text-align: right; }
        .accent { color: #d65f13; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div class="logo-wrap">
                        <img src="file://{{ public_path('img/front/booking/logo-pdf.png') }}" alt="ONX Logo">
                    </div>
                </td>
                <td class="brand-cell">
                    <p class="brand">ONX EDGE</p>
                    <p class="brand-sub">Production visuelle &bull; Evenementiel &bull; Publicite</p>
                </td>
                <td class="doc-cell">
                    <p class="doc-title">Bon de reservation</p>
                    <div class="doc-ref">
                        Reference #{{ $booking->id }}<br>
                        Date: {{ now()->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
        </table>
        <div class="notice">
            Ce document confirme l'enregistrement de la reservation. La validation finale est effectuee apres versement de l'acompte et confirmation par ONX.
        </div>
    </div>

    {{-- Informations --}}
    <div class="section">
        <p class="section-title">Informations</p>
        <table class="two-col">
            <tr>
                <td>
                    <div class="box">
                        <p class="box-title">Informations du client</p>
                        <div class="item">
                            <span class="label">Nom complet</span>
                            <span class="value">{{ $booking->name }}</span>
                        </div>
                        <div class="item">
                            <span class="label">Telephone</span>
                            <span class="value">{{ $booking->phone }}</span>
                        </div>
                        @if($booking->email)
                        <div class="item">
                            <span class="label">E-mail</span>
                            <span class="value">{{ $booking->email }}</span>
                        </div>
                        @endif
                        @if(!empty($booking->business_name))
                        <div class="item">
                            <span class="label">Activite</span>
                            <span class="value">{{ $booking->business_name }}</span>
                        </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div class="box">
                        <p class="box-title">Informations de reservation</p>
                        <div class="item">
                            <span class="label">Service</span>
                            <span class="value">{{ $booking->service?->slug ?? $booking->service?->name ?? '—' }}</span>
                        </div>
                        @if($packageName)
                        <div class="item">
                            <span class="label">Forfait</span>
                            <span class="value">{{ $packageName }}</span>
                        </div>
                        @endif
                        @if($booking->event_date)
                        <div class="item">
                            <span class="label">Date de l'evenement</span>
                            <span class="value">{{ $booking->event_date->format('d/m/Y') }}</span>
                        </div>
                        @endif
                        @if($locationName)
                        <div class="item">
                            <span class="label">Lieu</span>
                            <span class="value">{{ $locationName }}</span>
                        </div>
                        @endif
                        @if($booking->deadline)
                        <div class="item">
                            <span class="label">Date souhaitee</span>
                            <span class="value">{{ \Carbon\Carbon::parse($booking->deadline)->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Contenu du forfait --}}
    @php
    $pkg = $booking->package;
    if ($pkg) {
        $pkg->load('serviceItems');
        $features = $pkg->features;
    } else {
        $features = [];
    }
@endphp
    @if(!empty($features))
    <div class="section">
        <p class="section-title">Contenu du forfait — {{ $packageName }}</p>
        <table class="features-table">
            @foreach($features as $feature)
            <tr>
                <td>&#10003; &nbsp;{{ $feature }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    {{-- Montants --}}
    @php
        $paid      = $booking->paidAmount();
        $total     = (float)($booking->final_price ?? $booking->total_price ?? 0);
        $remaining = $booking->remainingAmount();
    @endphp
    <div class="section">
        <p class="section-title">Montant</p>
        <table class="money-table">
            <tr>
                <td>
                    <div class="money-box total">
                        <span class="money-label">Prix total</span>
                        <div class="money-value">
                            @if($total > 0){{ number_format($total, 0) }} DA
                            @else A confirmer @endif
                        </div>
                        <span class="money-sub">Montant global</span>
                    </div>
                </td>
                <td>
                    <div class="money-box paid">
                        <span class="money-label">Acompte verse</span>
                        <div class="money-value">
                            @if($paid > 0){{ number_format($paid, 0) }} DA
                            @else ................ @endif
                        </div>
                        <span class="money-sub">Montant regle</span>
                    </div>
                </td>
                <td>
                    <div class="money-box remaining">
                        <span class="money-label">Reste a payer</span>
                        <div class="money-value">
                            @if($remaining > 0){{ number_format($remaining, 0) }} DA
                            @elseif($total > 0) Solde
                            @else ................ @endif
                        </div>
                        <span class="money-sub">Solde restant</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Notes --}}
    @if(!empty($booking->notes))
    <div class="section">
        <p class="section-title">Notes</p>
        <div class="notes-box">{{ $booking->notes }}</div>
    </div>
    @endif

    {{-- Conditions --}}
    <div class="section">
        <p class="section-title">&#9888; Conditions</p>
        <div class="terms">
            <ul>
                @if($booking->isEvent())
                    <li>La reservation doit etre confirmee dans un delai de 7 jours.</li>
                    <li>L'acompte verse n'est pas remboursable.</li>
                    <li>Le client doit respecter les horaires convenus.</li>
                    <li>Tout depassement horaire apres 04h00 entraine des frais par tranche de 30 minutes.</li>
                @else
                    <li>La commande est confirmee apres validation du devis, du brief client et versement de l'acompte convenu.</li>
                    <li>L'acompte verse n'est pas remboursable des le lancement de la prestation.</li>
                    <li>Le client s'engage a fournir tous les elements necessaires dans les delais convenus.</li>
                    <li>Toute modification apres validation peut entrainer des frais supplementaires.</li>
                    <li>La livraison finale est effectuee apres reglement complet, sauf accord ecrit contraire.</li>
                    <li>Les creations restent la propriete d'ONX jusqu'au paiement integral.</li>
                @endif
            </ul>
        </div>
    </div>

    {{-- الحاوية السفلية المحدثة لثبات تام في صفحة واحدة --}}
    <div class="bottom-container">
        {{-- Signatures --}}
        <table class="sign-table">
            <tr>
                <td>
                    <div class="sign-box">
                        <div class="sign-title">Signature du client</div>
                        <div class="sign-line">Nom / Signature / Date</div>
                    </div>
                </td>
                <td>
                    <div class="sign-box">
                        <div class="sign-title">Validation ONX</div>
                        <div class="sign-line">Cachet / Signature / Date</div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Footer --}}
        <div class="footer">
            <table class="footer-table">
                <tr>
                    <td>
                        <strong>ONX EDGE</strong><br>
                        <span class="accent">Bon de reservation</span>
                    </td>
                    <td class="text-right">
                        Instagram: @onx.edge<br>
                        YouTube: @onxedge<br>
                        WhatsApp: +213 540 57 35 18
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>
</body>
</html>