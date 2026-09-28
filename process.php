<?php include 'header.php'; ?>
<div class="page active" id="page-process">

  <style>
    .ptags {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 20px;
    }

    .ptag {
      font-family: "Quicksand", sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 7px 16px;
      border: 1px solid rgba(var(--g-rgb), 0.4);
      border-radius: 999px;
      color: var(--n);
      background: rgba(var(--g-rgb), 0.08);
    }

    .proc-guar .pgi-p {
      color: rgba(25, 43, 69, 0.65);
    }

    #page-process .proc-steps {
      padding-top: 0;
    }

    .pstep-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 28px;
      margin-top: 56px;
    }

    .pstep-card {
      position: relative;
      display: flex;
      flex-direction: column;
      height: 100%;
      background: var(--w);
      border: 1px solid rgba(25, 43, 69, 0.08);
      border-radius: 18px;
      padding: 40px 32px 36px;
      overflow: hidden;
      transition:
        transform 0.4s cubic-bezier(0.19, 1, 0.22, 1),
        box-shadow 0.4s ease,
        border-color 0.4s ease;
    }

    .pstep-card::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--g);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.5s cubic-bezier(0.19, 1, 0.22, 1);
    }

    .pstep-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 24px 48px rgba(25, 43, 69, 0.12);
      border-color: rgba(221, 168, 81, 0.35);
    }

    .pstep-card:hover::before {
      transform: scaleX(1);
    }

    .pstep-card-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 22px;
      gap: 12px;
    }

    .pstep-card-n {
      font-family: "League Spartan", sans-serif;
      font-size: 52px;
      font-weight: 900;
      line-height: 1;
      color: rgba(221, 168, 81, 0.3);
    }

    .pstep-card-badge {
      background: var(--g);
      padding: 6px 14px;
      border-radius: 999px;
      margin-top: 6px;
      flex-shrink: 0;
      white-space: nowrap;
    }

    .pstep-card-badge span {
      font-family: "Quicksand", sans-serif;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.16em;
      text-transform: uppercase;
      color: var(--n);
    }

    .pstep-card-h {
      font-family: "League Spartan", sans-serif;
      font-size: 22px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: -0.01em;
      color: var(--n);
      margin-bottom: 14px;
    }

    .pstep-card-p {
      font-size: 14px;
      font-weight: 500;
      line-height: 1.8;
      color: rgba(25, 43, 69, 0.6);
      margin-bottom: 20px;
    }

    .pstep-card .ptags {
      margin-top: auto;
    }

    @media (max-width: 1100px) {
      .pstep-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 22px;
      }
    }

    @media (max-width: 700px) {
      .pstep-grid {
        grid-template-columns: 1fr;
        gap: 18px;
        margin-top: 40px;
      }

      .pstep-card {
        padding: 32px 24px 28px;
        border-radius: 14px;
      }

      .pstep-card-n {
        font-size: 42px;
      }

      .pstep-card-h {
        font-size: 19px;
      }
    }

    @media (max-width: 400px) {
      .pstep-card-top {
        flex-direction: column;
      }
    }
  </style>

  <div class="ph">
    <div class="ph-pbg hero-bg-img" data-speed="0.45" id="hero-bg"></div>
    <div class="ph-veil"></div>
    <div class="ph-body">
      <div class="ph-tag">
        <span class="ph-tag-line"></span><span>How It Works</span>
      </div>
      <h1 class="ph-title">Your Home,<br /><span class="gold">Our Process</span></h1>
      <p class="ph-sub">
        From your first conversation to the final handover — every step is
        thoughtful, transparent, and designed around you.
      </p>
    </div>
  </div>

  <section class="proc-intro">
    <div class="rv">
      <div class="eb">
        <div class="eb-line"></div>
        <span class="eb-t">Step By Step</span>
      </div>
      <h2 class="stitle">
        A Journey Built<br /><span class="th">Around You</span>
      </h2>
    </div>
    <p class="proc-body rv" data-d="1">
      Six clear stages take your home from first conversation to final
      handover. Nothing rushed, nothing hidden — just a considered process
      designed to feel as good as the result.
    </p>
  </section>

  <section class="proc-steps">

    <div class="pstep-grid">

      <div class="pstep-card rv" data-d="1">
        <div class="pstep-card-top">
          <div class="pstep-card-n">01</div>
          <div class="pstep-card-badge"><span>Step 01</span></div>
        </div>
        <h3 class="pstep-card-h">Consultation &amp; Briefing</h3>
        <p class="pstep-card-p">
          We begin by truly listening. Your dedicated designer meets you to
          understand your lifestyle, aspirations, and space — before a
          single sketch is made.
        </p>
        <div class="ptags">
          <span class="ptag">Welcome call</span>
          <span class="ptag">Site overview</span>
          <span class="ptag">Expectations shared</span>
        </div>
      </div>

      <div class="pstep-card rv" data-d="2">
        <div class="pstep-card-top">
          <div class="pstep-card-n">02</div>
          <div class="pstep-card-badge"><span>Step 02</span></div>
        </div>
        <h3 class="pstep-card-h">Site Measurement</h3>
        <p class="pstep-card-p">
          Our team visits your home to take precise measurements. This
          ensures every design is accurate to your actual space — not a
          generic template.
        </p>
        <div class="ptags">
          <span class="ptag">Home visit</span>
          <span class="ptag">3D SketchUp draft</span>
          <span class="ptag">~5 days</span>
        </div>
      </div>

      <div class="pstep-card rv" data-d="3">
        <div class="pstep-card-top">
          <div class="pstep-card-n">03</div>
          <div class="pstep-card-badge"><span>Step 03</span></div>
        </div>
        <h3 class="pstep-card-h">Design Presentation</h3>
        <p class="pstep-card-p">
          We present your personalised design — mood boards, layout
          options, material choices, and a detailed quote. You refine it
          until it feels completely right.
        </p>
        <div class="ptags">
          <span class="ptag">Mood boards</span>
          <span class="ptag">3D models</span>
          <span class="ptag">Colour &amp; laminates</span>
          <span class="ptag">Final quote</span>
        </div>
      </div>

      <div class="pstep-card rv" data-d="4">
        <div class="pstep-card-top">
          <div class="pstep-card-n">04</div>
          <div class="pstep-card-badge"><span>Step 04</span></div>
        </div>
        <h3 class="pstep-card-h">Design Sign-off</h3>
        <p class="pstep-card-p">
          Once you're fully happy, you approve the final drawings. This is
          the moment designs are locked — giving you complete confidence
          before production begins.
        </p>
        <div class="ptags">
          <span class="ptag">Final drawings</span>
          <span class="ptag">Your approval</span>
          <span class="ptag">No surprises after</span>
        </div>
      </div>

      <div class="pstep-card rv" data-d="5">
        <div class="pstep-card-top">
          <div class="pstep-card-n">05</div>
          <div class="pstep-card-badge"><span>Step 05</span></div>
        </div>
        <h3 class="pstep-card-h">Production &amp; Quality Check</h3>
        <p class="pstep-card-p">
          Your interiors are built in our controlled factory — not
          assembled on-site. Every piece goes through a rigorous QC
          inspection before it reaches your home.
        </p>
        <div class="ptags">
          <span class="ptag">Factory-built</span>
          <span class="ptag">QC verified</span>
          <span class="ptag">Premium materials</span>
        </div>
      </div>

      <div class="pstep-card rv" data-d="6">
        <div class="pstep-card-top">
          <div class="pstep-card-n">06</div>
          <div class="pstep-card-badge"><span>Step 06</span></div>
        </div>
        <h3 class="pstep-card-h">Installation &amp; Handover</h3>
        <p class="pstep-card-p">
          Our team installs with precision. On handover, we walk through
          every detail together — and only close the project when you're
          completely satisfied.
        </p>
        <div class="ptags">
          <span class="ptag">On-site installation</span>
          <span class="ptag">Final walkthrough</span>
          <span class="ptag">Ready to live in</span>
        </div>
      </div>

    </div>

  </section>

  <section class="proc-guar">
    <div class="rv">
      <div class="eb">
        <div class="eb-line"></div>
        <span class="eb-t">Our Promise</span>
      </div>
      <h2 class="stitle">
        Guaranteed With<br /><span class="th">Every Project</span>
      </h2>
    </div>
    <p class="proc-body rv" data-d="1" style="color: rgba(25, 43, 69, 0.65)">
      Every project comes with our quality guarantee, transparent timeline,
      and a dedicated designer from start to finish — because for us,
      quality always comes first.
    </p>

    <div class="pg-grid" style="margin-top: 56px">
      <div class="pgi rv" data-d="1">
        <div class="pgi-n">01</div>
        <div class="pgi-h">Systemised</div>
        <p class="pgi-p">
          Every step mapped, tracked, and repeatable — nothing is left to
          chance.
        </p>
      </div>
      <div class="pgi rv" data-d="2">
        <div class="pgi-n">02</div>
        <div class="pgi-h">Transparent</div>
        <p class="pgi-p">
          Clear pricing and honest timelines, shared upfront and honoured
          throughout.
        </p>
      </div>
      <div class="pgi rv" data-d="3">
        <div class="pgi-n">03</div>
        <div class="pgi-h">Collaborative</div>
        <p class="pgi-p">
          You stay involved and informed from the first sketch to the
          final key handover.
        </p>
      </div>
      <div class="pgi rv" data-d="4">
        <div class="pgi-n">04</div>
        <div class="pgi-h">Accountable</div>
        <p class="pgi-p">
          One team, fully responsible — backed by our guarantee from
          consultation to handover.
        </p>
      </div>
    </div>

    <div class="rv" data-d="2" style="text-align: center; margin-top: 56px">
      <a class="mbtn dk" href="contact.php#book-consultation">
        <div class="mbtn-bg"></div>
        <span class="mbtn-lbl">Book a Consultation</span>
      </a>
    </div>
  </section>

</div>

<?php include 'footer.php'; ?>
