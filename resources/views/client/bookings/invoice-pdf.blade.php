<!DOCTYPE html>
<html dir="ltr" lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'DejaVu Sans', sans-serif;
    background: #fff;
    color: #0c0f14;
    font-size: 13px;
    direction: ltr;
  }

  .inv-header {
    background: #0c0f14;
    color: #fff;
    padding: 28px 32px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }
  .inv-brand { font-size: 26px; font-weight: 900; letter-spacing: -1px; }
  .inv-brand span { color: #f5a623; }
  .inv-brand-sub { font-size: 11px; color: rgba(255,255,255,.5); margin-top: 4px; }
  .inv-meta { text-align: right; }
  .inv-title { font-size: 20px; font-weight: 800; color: #f5a623; margin-bottom: 6px; }
  .inv-num { font-size: 12px; color: rgba(255,255,255,.6); }

  .inv-ribbon {
    background: #f5a623;
    color: #000;
    text-align: center;
    padding: 6px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
  }

  .inv-body { padding: 28px 32px; }

  .inv-two-col { display: flex; gap: 20px; margin-bottom: 24px; }
  .inv-col {
    flex: 1;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 16px;
  }
  .inv-col-title {
    font-size: 10px;
    font-weight: 800;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e5e7eb;
  }
  .inv-col-row { margin-bottom: 6px; }
  .inv-col-label { font-size: 10px; color: #64748b; }
  .inv-col-val { font-size: 13px; font-weight: 700; color: #0c0f14; }

  .inv-progress {
    display: flex;
    align-items: center;
    gap: 0;
    margin-bottom: 24px;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 20px;
  }
  .inv-step { flex: 1; text-align: center; position: relative; }
  .inv-step-circle {
    width: 30px; height: 30px;
    border-radius: 50%;
    margin: 0 auto 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px; font-weight: 800;
    border: 2px solid #e5e7eb;
    background: #fff; color: #94a3b8;
  }
  .inv-step.done .inv-step-circle { background: #f5a623; border-color: #f5a623; color: #000; }
  .inv-step.active .inv-step-circle { background: #fff; border-color: #f5a623; color: #f5a623; }
  .inv-step-label { font-size: 10px; color: #64748b; font-weight: 700; }
  .inv-step.done .inv-step-label,
  .inv-step.active .inv-step-label { color: #0c0f14; }
  .inv-step-line { flex: 1; height: 2px; background: #e5e7eb; margin-bottom: 20px; }
  .inv-step-line.done { background: #f5a623; }

  .inv-section-title {
    font-size: 11px; font-weight: 800; color: #94a3b8;
    text-transform: uppercase; letter-spacing: .1em;
    margin: 20px 0 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e5e7eb;
  }

  .inv-pay-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  .inv-pay-table th {
    background: #1e2736; color: rgba(255,255,255,.8);
    padding: 8px 12px; font-size: 10px; font-weight: 800; text-align: left;
  }
  .inv-pay-table td {
    padding: 9px 12px; border-bottom: 1px solid #f1f5f9;
    font-size: 12px; text-align: left;
  }
  .inv-pay-table tr:nth-child(even) td { background: #f8fafc; }

  .inv-totals { width: 280px; margin-left: auto; }
  .inv-total-row {
    display: flex; justify-content: space-between;
    padding: 7px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px;
  }
  .inv-total-row.final { font-size: 16px; font-weight: 900; border-bottom: none; padding-top: 10px; }
  .inv-total-row.remaining { color: #ef4444; font-weight: 700; }
  .inv-total-row.paid-val { color: #16a34a; font-weight: 700; }

  .inv-footer {
    background: #f8fafc; border-top: 1px solid #e5e7eb;
    padding: 16px 32px; display: flex;
    justify-content: space-between; align-items: center;
    font-size: 10px; color: #94a3b8;
  }
</style>
</head>
<body>

<div class="inv-header">
    <div>
        <div class="inv-brand">ON<span>X</span></div>
        <div class="inv-brand-sub">
            {{ $companySettings?->company_name ?? 'ONX Media' }}<br>
            {{ $companySettings?->phone ?? '' }}
        </div>
    </div>
    <div class="inv-meta">
        <div class="inv-title">FACTURE / Invoice</div>
        <div class="inv-num">
            Reservation No: #{{ $booking->id }}<br>
            Date: {{ now()->format('d/m/Y') }}
        </div>
    </div>
</div>

<div class="inv-ribbon">
    @if($booking->isFullyPaid()) PAYE EN TOTALITE
    @elseif($booking->paidAmount() > 0) PAIEMENT PARTIEL — {{ $booking->paymentPercent() }}%
    @else EN ATTENTE DE PAIEMENT
    @endif
</div>

<div class="inv-body">

    <div class="inv-two-col">
        <div class="inv-col">
            <div class="inv-col-title">Client Information</div>
            <div class="inv-col-row">
                <div class="inv-col-label">Nom</div>
                <div class="inv-col-val">{{ $client->name }}</div>
            </div>
            @if($client->phone)
            <div class="inv-col-row">
                <div class="inv-col-label">Telephone</div>
                <div class="inv-col-val">{{ $client->phone }}</div>
            </div>
            @endif
            @if($client->email)
            <div class="inv-col-row">
                <div class="inv-col-label">Email</div>
                <div class="inv-col-val">{{ $client->email }}</div>
            </div>
            @endif
        </div>
        <div class="inv-col">
            <div class="inv-col-title">Details de la Prestation</div>
            <div class="inv-col-row">
                <div class="inv-col-label">Service</div>
                <div class="inv-col-val">{{ $booking->service?->name ?? '—' }}</div>
            </div>
            @if($meta['packageName'])
            <div class="inv-col-row">
                <div class="inv-col-label">Forfait</div>
                <div class="inv-col-val">{{ $meta['packageName'] }}</div>
            </div>
            @endif
            @if($booking->event_date)
            <div class="inv-col-row">
                <div class="inv-col-label">Date de l'evenement</div>
                <div class="inv-col-val">{{ $booking->event_date->format('d/m/Y') }}</div>
            </div>
            @endif
            @if($meta['locationName'])
            <div class="inv-col-row">
                <div class="inv-col-label">Lieu</div>
                <div class="inv-col-val">{{ $meta['locationName'] }}</div>
            </div>
            @endif
            <div class="inv-col-row">
                <div class="inv-col-label">Statut</div>
                <div class="inv-col-val">{{ $booking->statusLabel() }}</div>
            </div>
        </div>
    </div>

    {{-- Progress --}}
    @php $step = $booking->statusStep(); @endphp
    <div class="inv-progress">
        <div class="inv-step {{ $step >= 1 ? ($step > 1 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 1 ? 'v' : '1' }}</div>
            <div class="inv-step-label">Recu</div>
        </div>
        <div class="inv-step-line {{ $step > 1 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 2 ? ($step > 2 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 2 ? 'v' : '2' }}</div>
            <div class="inv-step-label">Confirme</div>
        </div>
        <div class="inv-step-line {{ $step > 2 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 3 ? ($step > 3 ? 'done' : 'active') : '' }}">
            <div class="inv-step-circle">{{ $step > 3 ? 'v' : '3' }}</div>
            <div class="inv-step-label">En cours</div>
        </div>
        <div class="inv-step-line {{ $step > 3 ? 'done' : '' }}"></div>
        <div class="inv-step {{ $step >= 4 ? 'done' : '' }}">
            <div class="inv-step-circle">{{ $step >= 4 ? 'v' : '4' }}</div>
            <div class="inv-step-label">Termine</div>
        </div>
    </div>

    @if($booking->total_price)
    <div class="inv-section-title">Historique des Paiements</div>

    @if($booking->payments->isNotEmpty())
    <table class="inv-pay-table">
        <thead>
            <tr>
                <th>Type</th>
                <th>Methode</th>
                <th>Reference</th>
                <th>Date</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($booking->payments as $pay)
            <tr>
                <td>{{ $pay->typeLabel() }}</td>
                <td>{{ $pay->methodLabel() }}</td>
                <td>{{ $pay->reference ?? '—' }}</td>
                <td>{{ $pay->paid_at->format('d/m/Y') }}</td>
                <td><strong>{{ number_format($pay->amount, 0) }} DA</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="inv-totals">
        <div class="inv-total-row">
            <span>Prix total convenu</span>
            <span>{{ number_format($booking->total_price, 0) }} DA</span>
        </div>
        <div class="inv-total-row paid-val">
            <span>Total paye</span>
            <span>{{ number_format($booking->paidAmount(), 0) }} DA</span>
        </div>
        @if($booking->remainingAmount() > 0)
        <div class="inv-total-row remaining">
            <span>Reste a payer</span>
            <span>{{ number_format($booking->remainingAmount(), 0) }} DA</span>
        </div>
        @endif
        <div class="inv-total-row final">
            <span>Taux de paiement</span>
            <span>{{ $booking->paymentPercent() }}%</span>
        </div>
    </div>
    @endif

    @if($booking->notes)
    <div class="inv-section-title">Notes</div>
    <p style="font-size:12px;color:#374151;line-height:1.8;">{{ $booking->notes }}</p>
    @endif

</div>

<div class="inv-footer">
    <span>Merci de votre confiance — ONX</span>
    <span>
        {{ $companySettings?->phone ?? '' }}
        {{ $companySettings?->email ? ' · ' . $companySettings->email : '' }}
    </span>
    <span>Emis le: {{ now()->format('d/m/Y H:i') }}</span>
</div>

</body>
</html>