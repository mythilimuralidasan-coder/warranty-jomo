    <?php
    include 'header.php';
    function e($value)
    {
      return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
    
    function project_label(array $categories)
    {
      $labels = array(
        'living' => 'Living Spaces',
        'kitchen' => 'Kitchen',
        'bedroom' => 'Bedroom',
      );
    
      $first = $categories[0] ?? 'living';
      return $labels[$first] ?? ucfirst((string) $first);
    }
    
    function project_covers($hero, array $covers = array())
    {
      return array_merge(array(
        'all' => $hero,
        'living' => $hero,
        'kitchen' => $hero,
        'bedroom' => $hero,
      ), $covers);
    }
    
    $projects = array(
      array(
        'slug' => 'courtyard',
        'name' => 'Courtyard',
        'hero' => 'assets/Images/Courtyard/coutryard4.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Courtyard/coutryard4.png', array(
          'living' => 'assets/Images/Courtyard/coutryard4.png',
          'kitchen' => 'assets/Images/Courtyard/coutryard4.png',
          'bedroom' => 'assets/Images/Courtyard/coutryard7.png',
        )),
        'gallery' => array(
          'assets/Images/Courtyard/coutryard1.png',
          'assets/Images/Courtyard/coutryard3.png',
          'assets/Images/Courtyard/coutryard2.png',
          'assets/Images/Courtyard/coutryard6.png',
          'assets/Images/Courtyard/coutryard4.png',
          'assets/Images/Courtyard/coutryard7.png',
          'assets/Images/Courtyard/coutryard5.png',
          'assets/Images/Courtyard/coutryard8.png',
        ),
      ),
      array(
        'slug' => 'suraj',
        'name' => 'Mr. Suraj',
        'hero' => 'assets/Images/Suraj/suraj4.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Suraj/suraj4.png', array(
          'living' => 'assets/Images/Suraj/suraj6.png',
          'kitchen' => 'assets/Images/Suraj/suraj7.png',
          'bedroom' => 'assets/Images/Suraj/suraj4.png',
        )),
        'gallery' => array(
          'assets/Images/Suraj/suraj4.png',
          'assets/Images/Suraj/suraj3.png',
          'assets/Images/Suraj/suraj6.png',
          'assets/Images/Suraj/suraj1.png',
          'assets/Images/Suraj/suraj2.png',
          'assets/Images/Suraj/suraj5.png',
          'assets/Images/Suraj/suraj7.png',
        
        ),
      ),
     array(
        'slug' => 'priya',
        'name' => 'Ms. Priya',
        'hero' => 'assets/Images/Priya/Priya4.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Priya/Priya4.png', array(
          'living' => 'assets/Images/Priya/Priya4.png',
          'kitchen' => 'assets/Images/Priya/Priya4.png',
          'bedroom' => 'assets/Images/Priya/Priya4.png',
        )),
        'gallery' => array(
          'assets/Images/Priya/Priya7.png',
          'assets/Images/Priya/Priya3.png',
          'assets/Images/Priya/Priya1.png',
          'assets/Images/Priya/Priya2.png',
          'assets/Images/Priya/Priya5.png',
          'assets/Images/Priya/Priya4.png',
          'assets/Images/Priya/Priya6.png',
        ),
      ),
      array(
        'slug' => 'pournima',
        'name' => 'Ms. Poornima',
        'hero' => 'assets/Images/Pournima/Pournima4.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Pournima/Pournima4.png', array(
          'living' => 'assets/Images/Pournima/Pournima2.png',
          'kitchen' => 'assets/Images/Pournima/Pournima4.png',
          'bedroom' => 'assets/Images/Pournima/Pournima1.png',
        )),
        'gallery' => array(
          'assets/Images/Pournima/Pournima4.png',
          'assets/Images/Pournima/Pournima5.png',
          'assets/Images/Pournima/Pournima2.png',
          'assets/Images/Pournima/Pournima3.png',
          'assets/Images/Pournima/Pournima1.png',
          'assets/Images/Pournima/Pournima6.png',
        ),
      ),
      array(
        'slug' => 'mohan',
        'name' => 'Mr. Mohan',
        'hero' => 'assets/Images/Mohan/Mohan2.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Mohan/Mohan2.png', array(
          'living' => 'assets/Images/Mohan/Mohan1.png',
          'kitchen' => 'assets/Images/Mohan/Mohan2.png',
          'bedroom' => 'assets/Images/Mohan/Mohan5.png',
        )),
        'gallery' => array(
          'assets/Images/Mohan/Mohan2.png',
          'assets/Images/Mohan/Mohan7.png',
          'assets/Images/Mohan/Mohan6.png',
          'assets/Images/Mohan/Mohan1.png',
          'assets/Images/Mohan/Mohan4.png',
          'assets/Images/Mohan/Mohan5.png',
          'assets/Images/Mohan/Mohan3.png',
        ),
      ),
      array(
        'slug' => 'krishna-priya',
        'name' => 'Ms. Krishna Priya',
        'hero' => 'assets/Images/Krishana/Krishana1.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Krishana/Krishana1.png', array(
          'living' => 'assets/Images/Krishana/Krishana4.png',
          'kitchen' => 'assets/Images/Krishana/Krishana7.png',
          'bedroom' => 'assets/Images/Krishana/Krishana1.png',
        )),
        'gallery' => array(
          'assets/Images/Krishana/Krishana1.png',
          'assets/Images/Krishana/Krishana7.png',
          'assets/Images/Krishana/Krishana4.png',
          'assets/Images/Krishana/Krishana6.png',
          'assets/Images/Krishana/Krishana3.png',
          'assets/Images/Krishana/Krishana5.png',
          'assets/Images/Krishana/Krishana2.png',
        ),
      ),
      array(
        'slug' => 'preethi',
        'name' => 'Ms. Preethi',
        'hero' => 'assets/Images/Preethi/preethi4.png',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Preethi/preethi4.png', array(
          'living' => 'assets/Images/Preethi/preethi1.png',
          'kitchen' => 'assets/Images/Preethi/preethi4.png',
          'bedroom' => 'assets/Images/Preethi/preethi6.png',
        )),
        'gallery' => array(
          'assets/Images/Preethi/preethi4.png',
          'assets/Images/Preethi/preethi5.png',
          'assets/Images/Preethi/preethi1.png',
          'assets/Images/Preethi/preethi2.png',
          'assets/Images/Preethi/preethi3.png',
          'assets/Images/Preethi/preethi6.png',
          'assets/Images/Preethi/preethi8.png',
          'assets/Images/Preethi/preethi7.png',
        ),
      ),
      array(
        'slug' => 'dilip',
        'name' => 'Mr. Dilip',
        'hero' => 'assets/Images/Dilip/Dilip1.webp',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Dilip/Dilip1.webp', array(
          'living' => 'assets/Images/Dilip/Dilip1.webp',
          'kitchen' => 'assets/Images/Dilip/Dilip6.webp',
          'bedroom' => 'assets/Images/Dilip/Dilip3.webp',
        )),
        'gallery' => array(
          'assets/Images/Dilip/Dilip1.webp',
          'assets/Images/Dilip/Dilip6.webp',
          'assets/Images/Dilip/Dilip5.webp',
          'assets/Images/Dilip/Dilip3.webp',
          'assets/Images/Dilip/Dilip4.webp',
          'assets/Images/Dilip/Dilip2.webp',
        ),
      ),
      array(
        'slug' => 'hemanth',
        'name' => 'Mr. Hemanth',
        'hero' => 'assets/Images/Hemanth/Hemanth1.webp',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Hemanth/Hemanth1.webp', array(
          'living' => 'assets/Images/Hemanth/Hemanth1.webp',
          'kitchen' => 'assets/Images/Hemanth/Hemanth6.webp',
          'bedroom' => 'assets/Images/Hemanth/Hemanth2.webp',
        )),
        'gallery' => array(
          'assets/Images/Hemanth/Hemanth1.webp',
          'assets/Images/Hemanth/Hemanth8.webp',
          'assets/Images/Hemanth/Hemanth4.webp',
          'assets/Images/Hemanth/Hemanth6.webp',
          'assets/Images/Hemanth/Hemanth2.webp',
          'assets/Images/Hemanth/Hemanth3.webp',
          'assets/Images/Hemanth/Hemanth5.webp',
          'assets/Images/Hemanth/Hemanth7.webp',
        ),
      ),
      array(
        'slug' => 'rohan',
        'name' => 'Mr. Rohan',
        'hero' => 'assets/Images/Rohan/Rohan1.webp',
        'categories' => array('living', 'kitchen'),
        'covers' => project_covers('assets/Images/Rohan/Rohan1.webp', array(
          'living' => 'assets/Images/Rohan/Rohan1.webp',
          'kitchen' => 'assets/Images/Rohan/Rohan3.webp',
        )),
        'gallery' => array(
          'assets/Images/Rohan/Rohan1.webp',
          'assets/Images/Rohan/Rohan3.webp',
          'assets/Images/Rohan/Rohan2.webp',
          'assets/Images/Rohan/Rohan4.webp',
        ),
      ),
      array(
        'slug' => 'riyas',
        'name' => 'Riyas',
        'hero' => 'assets/Images/Palavakkam/Palavakkam3.webp',
        'categories' => array('living', 'kitchen'),
        'covers' => project_covers('assets/Images/Palavakkam/Palavakkam3.webp', array(
          'living' => 'assets/Images/Palavakkam/Palavakkam1.webp',
          'kitchen' => 'assets/Images/Palavakkam/Palavakkam3.webp',
        )),
        'gallery' => array(
          'assets/Images/Palavakkam/Palavakkam3.webp',
          'assets/Images/Palavakkam/Palavakkam1.webp',
          'assets/Images/Palavakkam/Palavakkam2.webp',
          'assets/Images/Palavakkam/Palavakkam4.webp',
        ),
      ),
      array(
        'slug' => 'jeeva',
        'name' => 'Jeeva',
        'hero' => 'assets/Images/Thiruvallur/Thiruvallur1.webp',
        'categories' => array('living', 'bedroom'),
        'covers' => project_covers('assets/Images/Thiruvallur/Thiruvallur1.webp', array(
          'living' => 'assets/Images/Thiruvallur/Thiruvallur1.webp',
          'bedroom' => 'assets/Images/Thiruvallur/Thiruvallur2.webp',
        )),
        'gallery' => array(
          'assets/Images/Thiruvallur/Thiruvallur1.webp',
          'assets/Images/Thiruvallur/Thiruvallur3.webp',
          'assets/Images/Thiruvallur/Thiruvallur2.webp',
          'assets/Images/Thiruvallur/Thiruvallur4.webp',
        ),
      ),
      array(
        'slug' => 'nemanth',
        'name' => 'Mr. Nemanth',
        'hero' => 'assets/Images/Nemanth/Nemanth1.webp',
        'categories' => array('living', 'kitchen', 'bedroom'),
        'covers' => project_covers('assets/Images/Nemanth/Nemanth1.webp', array(
          'living' => 'assets/Images/Nemanth/Nemanth1.webp',
          'kitchen' => 'assets/Images/Nemanth/Nemanth7.webp',
          'bedroom' => 'assets/Images/Nemanth/Nemanth3.webp',
        )),
        'gallery' => array(
          'assets/Images/Nemanth/Nemanth1.webp',
          'assets/Images/Nemanth/Nemanth6.webp',
          'assets/Images/Nemanth/Nemanth4.webp',
          'assets/Images/Nemanth/Nemanth2.webp',
          'assets/Images/Nemanth/Nemanth5.webp',
          'assets/Images/Nemanth/Nemanth3.webp',
          'assets/Images/Nemanth/Nemanth7.webp',
        ),
      ),
    );
    
    $projectsJs = array_map(function ($project) {
      return array(
        'slug' => $project['slug'],
        'name' => $project['name'],
        'hero' => $project['hero'],
        'categories' => $project['categories'],
        'covers' => $project['covers'],
        'gallery' => $project['gallery'],
        'label' => project_label($project['categories']),
      );
    }, $projects);
    ?>
    <div class="page active" id="page-projects">
      <div class="ph">
        <div class="ph-pbg hero-bg-img" data-speed="0.45" id="hero-bg"></div>
        <div class="ph-veil"></div>
        <div class="ph-body">
          <div class="ph-tag">
            <span class="ph-tag-line"></span><span>Our Work</span>
          </div>
          <h1 class="ph-title">
            Projects &<br /><span class="gold">Portfolio</span>
          </h1>
          <p class="ph-sub">
            200+ homes transformed across Chennai. Every project is a family dream
            realised, a life made more beautiful.
          </p>
        </div>
      </div>
    
      <div class="pf-bar" id="projectFilterBar" aria-label="Project filters">
        <button class="pf-btn act" type="button" data-filter="all">All Projects</button>
        <button class="pf-btn" type="button" data-filter="living">Living Spaces</button>
        <button class="pf-btn" type="button" data-filter="kitchen">Kitchen</button>
        <button class="pf-btn" type="button" data-filter="bedroom">Bedroom</button>
      </div>
    
      <div class="pgrid" id="projectGrid">
        <div class="pg">
          <?php foreach ($projects as $index => $project) : ?>
            <button
              class="pgcard rvs <?php echo ($index % 4 === 0) ? 'pgcard--feature' : (($index % 4 === 1) ? 'pgcard--tall' : 'pgcard--soft'); ?>"
              type="button"
              data-project-slug="<?php echo e($project['slug']); ?>"
              data-categories="<?php echo e(implode(' ', $project['categories'])); ?>"
              data-cover-all="<?php echo e($project['covers']['all']); ?>"
              data-cover-living="<?php echo e($project['covers']['living']); ?>"
              data-cover-kitchen="<?php echo e($project['covers']['kitchen']); ?>"
              data-cover-bedroom="<?php echo e($project['covers']['bedroom']); ?>"
              aria-label="Open <?php echo e($project['name']); ?> project"
            >
              <span
                class="pgcard-inner"
                style="background-image: url('<?php echo e($project['hero']); ?>');"
                data-parallax-img="0.18"
              ></span>
              <span class="pgcard-veil"></span>
              <span class="pgcard-info">
                <span class="pgcard-cat"><?php echo e(project_label($project['categories'])); ?></span>
                <span class="pgcard-name"><?php echo e($project['name']); ?></span>
              </span>
            </button>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    
    <div class="project-lightbox" id="projectLightbox" aria-hidden="true">
      <button class="plb-close" type="button" aria-label="Close project preview">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
          <path d="M6 6l12 12M18 6L6 18" />
        </svg>
      </button>
    
      <div class="plb-side plb-side-prev">
        <span class="plb-client-hint plb-prev-label"></span>
        <button class="plb-arrow plb-prev" type="button" aria-label="Previous project">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M15 6l-6 6 6 6" />
          </svg>
        </button>
      </div>
    
      <div class="plb-dialog" role="dialog" aria-modal="true" aria-label="Project image preview">
        <img class="plb-img" src="" alt="" />
        <div class="plb-caption">
          <span class="plb-kicker"></span>
          <h3 class="plb-title"></h3>
          <p class="plb-counter"></p>
        </div>
      </div>
    
      <div class="plb-side plb-side-next">
        <button class="plb-arrow plb-next" type="button" aria-label="Next project">
          <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M9 6l6 6-6 6" />
          </svg>
        </button>
        <span class="plb-client-hint plb-next-label"></span>
      </div>
    </div>
    
    <script>
      window.PROJECTS = <?php echo json_encode($projectsJs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
    </script>
    
    <script>
      (function () {
        const projects = Array.isArray(window.PROJECTS) ? window.PROJECTS : [];
        const cards = Array.from(document.querySelectorAll('.pgcard'));
        const filterBar = document.getElementById('projectFilterBar');
        const grid = document.getElementById('projectGrid');
        const lightbox = document.getElementById('projectLightbox');
    
        if (!projects.length || !cards.length || !lightbox || !filterBar || !grid) {
          return;
        }
    
        const image = lightbox.querySelector('.plb-img');
        const kicker = lightbox.querySelector('.plb-kicker');
        const title = lightbox.querySelector('.plb-title');
        const counter = lightbox.querySelector('.plb-counter');
        const closeBtn = lightbox.querySelector('.plb-close');
        const prevBtn = lightbox.querySelector('.plb-prev');
        const nextBtn = lightbox.querySelector('.plb-next');
        const prevLabel = lightbox.querySelector('.plb-prev-label');
        const nextLabel = lightbox.querySelector('.plb-next-label');
    
        const projectBySlug = new Map(projects.map((project) => [project.slug, project]));
        let activeFilter = 'all';
        let visibleCards = cards.slice();
        let activeProjectIndex = 0;
        let activeImageIndex = 0;
    
        function applyCardPreviews() {
          cards.forEach((card) => {
            const cover = activeFilter === 'all'
              ? card.dataset.coverAll
              : card.dataset['cover' + activeFilter.charAt(0).toUpperCase() + activeFilter.slice(1)];
            const imageUrl = cover || card.dataset.coverAll || '';
            const inner = card.querySelector('.pgcard-inner');
            if (inner && imageUrl) {
              inner.style.backgroundImage = 'url("' + imageUrl + '")';
            }
          });
        }
    
        function syncVisibleCards() {
          visibleCards = cards.filter((card) => {
            const categories = (card.dataset.categories || '').split(/\s+/).filter(Boolean);
            const show = activeFilter === 'all' || categories.indexOf(activeFilter) !== -1;
            card.classList.toggle('is-filter-hidden', !show);
            return show;
          });
    
          grid.classList.toggle('filtered-view', activeFilter !== 'all');
          grid.classList.toggle('all-view', activeFilter === 'all');
          applyCardPreviews();
        }
    
        function projectFromCard(card) {
          return projectBySlug.get(card.dataset.projectSlug);
        }
    
        function setSideLabels(index) {
          const showLabels = activeFilter === 'all' && visibleCards.length > 1;
          if (!showLabels) {
            if (prevLabel) {
              prevLabel.textContent = '';
              prevLabel.classList.remove('visible');
            }
            if (nextLabel) {
              nextLabel.textContent = '';
              nextLabel.classList.remove('visible');
            }
            return;
          }
    
          const prevCard = visibleCards[(index - 1 + visibleCards.length) % visibleCards.length];
          const nextCard = visibleCards[(index + 1) % visibleCards.length];
          const prevProject = projectFromCard(prevCard);
          const nextProject = projectFromCard(nextCard);
    
          if (prevLabel) {
            prevLabel.textContent = visibleCards.length > 1 && prevProject
              ? 'Previous project\n' + prevProject.name
              : '';
            prevLabel.classList.toggle('visible', !!prevLabel.textContent);
          }
    
          if (nextLabel) {
            nextLabel.textContent = visibleCards.length > 1 && nextProject
              ? 'Next project\n' + nextProject.name
              : '';
            nextLabel.classList.toggle('visible', !!nextLabel.textContent);
          }
        }
    
        function showProject(index, imageIndex) {
          if (!visibleCards.length) return;
    
          activeProjectIndex = (index + visibleCards.length) % visibleCards.length;
          const card = visibleCards[activeProjectIndex];
          const project = projectFromCard(card);
          if (!project) return;
    
          const gallery = project.gallery && project.gallery.length ? project.gallery : [project.hero];
          activeImageIndex = (typeof imageIndex === 'number')
            ? (imageIndex + gallery.length) % gallery.length
            : Math.min(activeImageIndex, gallery.length - 1);
          const currentImage = gallery[activeImageIndex];
    
          image.src = currentImage;
          image.alt = project.name + ' project image';
          kicker.textContent = project.label || 'Project';
          title.textContent = project.name;
          counter.textContent = (activeImageIndex + 1) + ' of ' + gallery.length;
    
          setSideLabels(activeProjectIndex);
        }
    
        function openLightboxBySlug(slug) {
          syncVisibleCards();
    
          const index = visibleCards.findIndex((card) => card.dataset.projectSlug === slug);
          if (index === -1) return;
    
          lightbox.classList.toggle('lightbox-minimal', activeFilter !== 'all');
          showProject(index, 0);
          lightbox.classList.add('is-open');
          lightbox.setAttribute('aria-hidden', 'false');
          document.body.style.overflow = 'hidden';
          history.replaceState(null, '', '#project=' + slug);
          closeBtn.focus();
        }
    
        function closeLightbox() {
          lightbox.classList.remove('is-open');
          lightbox.classList.remove('lightbox-minimal');
          lightbox.setAttribute('aria-hidden', 'true');
          document.body.style.overflow = '';
          if (location.hash.indexOf('#project=') === 0) {
            history.replaceState(null, '', location.pathname + location.search);
          }
        }
    
        cards.forEach((card) => {
          card.addEventListener('click', () => {
            openLightboxBySlug(card.dataset.projectSlug);
          });
    
          card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
              event.preventDefault();
              openLightboxBySlug(card.dataset.projectSlug);
            }
          });
        });
    
        filterBar.addEventListener('click', (event) => {
          const button = event.target.closest('.pf-btn');
          if (!button) return;
    
          filterBar.querySelectorAll('.pf-btn').forEach((btn) => btn.classList.remove('act'));
          button.classList.add('act');
          activeFilter = button.dataset.filter || 'all';
          syncVisibleCards();
        });
    
        prevBtn.addEventListener('click', () => {
          if (!visibleCards.length) return;
    
          if (activeImageIndex > 0) {
            showProject(activeProjectIndex, activeImageIndex - 1);
            return;
          }
    
          const previousProjectIndex = (activeProjectIndex - 1 + visibleCards.length) % visibleCards.length;
          const previousProject = projectFromCard(visibleCards[previousProjectIndex]);
          const previousGallery = previousProject && previousProject.gallery && previousProject.gallery.length
            ? previousProject.gallery
            : [previousProject ? previousProject.hero : image.src];
          showProject(previousProjectIndex, previousGallery.length - 1);
        });
    
        nextBtn.addEventListener('click', () => {
          if (!visibleCards.length) return;
    
          const card = visibleCards[activeProjectIndex];
          const project = projectFromCard(card);
          const gallery = project && project.gallery && project.gallery.length ? project.gallery : [project ? project.hero : image.src];
    
          if (activeImageIndex < gallery.length - 1) {
            showProject(activeProjectIndex, activeImageIndex + 1);
            return;
          }
    
          const nextProjectIndex = (activeProjectIndex + 1) % visibleCards.length;
          showProject(nextProjectIndex, 0);
        });
    
        closeBtn.addEventListener('click', closeLightbox);
    
        lightbox.addEventListener('click', (event) => {
          if (event.target === lightbox) {
            closeLightbox();
          }
        });
    
        document.addEventListener('keydown', (event) => {
          if (!lightbox.classList.contains('is-open')) return;
          if (event.key === 'Escape') closeLightbox();
          if (event.key === 'ArrowLeft') prevBtn.click();
          if (event.key === 'ArrowRight') nextBtn.click();
        });
    
        function openFromHash() {
          const hash = location.hash || '';
          const match = hash.match(/^#project=([a-z0-9-]+)$/i);
          if (!match) return;
          openLightboxBySlug(match[1]);
        }
    
        syncVisibleCards();
        openFromHash();
      })();
    </script>
    
    <?php include 'footer.php'; ?>
