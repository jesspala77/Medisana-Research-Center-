@php
    $synnexusLogo = $absoluteUrls
        ? url('images/outreach/synnexus-logo.png')
        : asset('images/outreach/synnexus-logo.png');
    $southernmostLogo = $absoluteUrls
        ? url('images/outreach/southernmost-surety-logo.svg')
        : asset('images/outreach/southernmost-surety-logo.svg');
    $palmettoLogo = $absoluteUrls
        ? url('images/outreach/palmetto-surety-logo.gif')
        : asset('images/outreach/palmetto-surety-logo.gif');
    $globalSynergiaLogo = $absoluteUrls
        ? url('images/outreach/global-synergia-group-logo.png')
        : asset('images/outreach/global-synergia-group-logo.png');
    $websiteHref = str_starts_with($website, 'http') ? $website : 'https://'.$website;
    $websiteDisplay = preg_replace('#^https?://#', '', $website);
@endphp

<table cellpadding="0" cellspacing="0" role="presentation" style="font-family: Arial, sans-serif; color:#111827; max-width:720px; width:100%; border:0; border-collapse:collapse; box-shadow:none; background:transparent;">
  <tr>
    <td style="width:180px; padding:0 20px 0 0; vertical-align:top; border-right:2px solid #b9934b;">
      <img src="{{ $synnexusLogo }}" alt="SynNexus" style="display:block; width:170px; max-width:170px; height:auto; border:0; border-radius:8px;">
    </td>

    <td style="padding-left:20px; vertical-align:top;">
      <div style="font-size:19px; font-weight:700; color:#0f172a; letter-spacing:0;">
        Jessica Palacio
      </div>

      <div style="font-size:13px; color:#4b5563; margin-top:3px; line-height:1.45;">
        Founder &amp; Director of Development | SynNexus
      </div>

      <div style="width:46px; height:2px; background:#b9934b; margin:12px 0;"></div>

      <div style="font-size:13px; line-height:1.7; color:#111827;">
        <strong>Direct:</strong> {{ $phone }}<br>
        <strong>Email:</strong> <a href="mailto:{{ $email }}" style="color:#0f172a; text-decoration:none;">{{ $email }}</a><br>
        <strong>Web:</strong> <a href="{{ $websiteHref }}" style="color:#0f172a; text-decoration:none;">{{ $websiteDisplay }}</a>
      </div>

      <div style="font-size:12px; color:#4b5563; margin-top:12px; line-height:1.5;">
        Bail bond agency growth, additional coverage, lower-rate opportunities, and electronic powers.
      </div>

      <div style="font-size:11px; color:#6b7280; margin-top:12px; text-transform:uppercase; letter-spacing:0; font-weight:700;">
        In partnership with
      </div>

      <table cellpadding="0" cellspacing="0" role="presentation" style="margin-top:8px; border:0; border-collapse:collapse; box-shadow:none; background:transparent;">
        <tr>
          <td style="padding:7px 12px 7px 0; vertical-align:middle;">
            <table cellpadding="0" cellspacing="0" role="presentation" style="border:0; border-collapse:collapse; box-shadow:none; background:#0f172a;">
              <tr>
                <td style="background:#0f172a; padding:7px 9px; vertical-align:middle;">
                  <img src="{{ $southernmostLogo }}" alt="Southernmost Surety" style="display:block; max-width:126px; max-height:52px; width:auto; height:auto; border:0;">
                </td>
              </tr>
            </table>
          </td>
          <td style="padding:7px 12px; vertical-align:middle;">
            <img src="{{ $palmettoLogo }}" alt="Palmetto Surety" style="display:block; max-width:118px; max-height:54px; width:auto; height:auto; border:0;">
          </td>
          <td style="padding:7px 0 7px 12px; vertical-align:middle;">
            <div style="border-left:1px solid #d1d5db; padding-left:12px;">
              <img src="{{ $globalSynergiaLogo }}" alt="Global Synergia Group" style="display:block; width:92px; max-width:92px; height:auto; border:0; margin-bottom:3px;">
              <div style="font-size:13px; font-weight:700; color:#0f172a; line-height:1.2;">Global Synergia Group</div>
              <div style="font-size:10px; color:#6b7280; line-height:1.3;">SynNexus Platform Developer</div>
            </div>
          </td>
        </tr>
      </table>

      <div style="font-size:11px; color:#9ca3af; margin-top:12px; line-height:1.5;">
        If this is not relevant, reply "remove" and I will not follow up.
      </div>
    </td>
  </tr>
</table>
