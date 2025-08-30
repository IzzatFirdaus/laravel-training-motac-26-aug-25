<!-- Shared email CSS for MYDS email templates: kept inline via include for email-client compatibility -->
<style>
  :root{
    --myds-primary:#2557eb;
    --myds-bg:#ffffff;
    --myds-surface:#fafafa;
    --myds-text:#0f172a;
    --myds-muted:#6b7280;
    --myds-border:#e6eef8;
    --myds-radius:8px;
  }

  html,body{margin:0;padding:0;background:var(--myds-surface);height:100%;width:100%;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;}
  body{font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;color:var(--myds-text);line-height:1.4;}
  .myds-container{max-width:680px;margin:0 auto;padding:24px;}
  .brand{display:block;font-weight:700;color:var(--myds-primary);font-size:18px;margin-bottom:12px;text-decoration:none;}
  .brand-logo{height:36px;margin-bottom:8px;display:block;border:0;}
  .myds-card{background:var(--myds-bg);border:1px solid var(--myds-border);border-radius:var(--myds-radius);padding:18px;}
  h1,h2{margin:0 0 8px 0;font-weight:600;color:var(--myds-text);}
  p{margin:0 0 12px 0;color:var(--myds-text);}
  .muted{color:var(--myds-muted);font-size:14px;margin:0 0 12px 0;}
  .meta{font-size:14px;color:var(--myds-text);margin:6px 0;word-break:break-word;}
  .meta-table{margin-top:8px;width:100%;}
  .meta-table .meta{padding:6px 0;}
  .description{margin-top:12px;color:var(--myds-text);font-size:14px;white-space:pre-wrap;}
  .action-row{margin-top:18px;}
  .small{font-size:12px;margin-top:8px;}
  .label{color:var(--myds-muted);font-weight:600;margin-right:6px;}
  .myds-btn{
    display:inline-block;padding:10px 16px;background:var(--myds-primary);color:#fff;border-radius:6px;text-decoration:none;font-weight:600;
  }
  .footer{font-size:13px;color:var(--myds-muted);margin-top:18px;}
  .visually-hidden{position:absolute!important;height:1px;width:1px;overflow:hidden;clip:rect(1px,1px,1px,1px);white-space:nowrap;border:0;padding:0;margin:-1px;}
  img{max-width:100%;height:auto;border:0;display:block;}
  @media (max-width:480px){
    .myds-container{padding:16px;}
    .myds-card{padding:16px;}
    .myds-btn{display:block;text-align:center;width:100%;}
  }
</style>
