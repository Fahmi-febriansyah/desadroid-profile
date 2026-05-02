/* ── Desadroid Premium JS ── */
(function(){
'use strict';

/* ── Scroll Reveal (replaces AOS) ── */
const revealEls = document.querySelectorAll('[data-reveal]');
const revealObs = new IntersectionObserver((entries)=>{
    entries.forEach((e,i)=>{
        if(e.isIntersecting){
            const d = e.target.dataset.revealDelay||0;
            setTimeout(()=>e.target.classList.add('revealed'), Number(d));
            revealObs.unobserve(e.target);
        }
    });
},{threshold:0.12, rootMargin:'0px 0px -40px 0px'});
revealEls.forEach(el=>revealObs.observe(el));

/* ── Staggered cards ── */
document.querySelectorAll('.card, .project, .project-card, .blog-card, .testimonial-card, .step, .stat, .related-card, .articles article').forEach((c,i)=>{
    c.setAttribute('data-reveal','');
    c.dataset.revealDelay = i * 80;
    revealObs.observe(c);
});

/* ── Navbar scroll effect ── */
const nav = document.querySelector('.navbar');
if(nav){
    let last=0;
    window.addEventListener('scroll',()=>{
        const y=window.scrollY;
        nav.classList.toggle('scrolled', y>40);
        nav.classList.toggle('nav-hidden', y>last && y>300);
        last=y;
    },{passive:true});
}

/* ── Mobile nav toggle ── */
const menuToggle = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');
if(menuToggle && navLinks){
    menuToggle.addEventListener('click',()=>{
        navLinks.classList.toggle('open');
        menuToggle.classList.toggle('active');
    });
    navLinks.querySelectorAll('a').forEach(link=>{
        link.addEventListener('click',()=>{
            navLinks.classList.remove('open');
            menuToggle.classList.remove('active');
        });
    });
    document.addEventListener('click',(e)=>{
        if(!e.target.closest('.navbar')){
            navLinks.classList.remove('open');
            menuToggle.classList.remove('active');
        }
    });
}

/* ── Smooth scroll for anchors ── */
document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click',(e)=>{
        const t=document.querySelector(a.getAttribute('href'));
        if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});}
    });
});

/* ── Counter animation ── */
const counters = document.querySelectorAll('.stat h3');
const counterObs = new IntersectionObserver((entries)=>{
    entries.forEach(e=>{
        if(!e.isIntersecting) return;
        const el=e.target, raw=el.textContent.trim(), num=parseInt(raw);
        if(isNaN(num)){counterObs.unobserve(el);return;}
        const suffix=raw.replace(String(num),'');
        let cur=0; const step=Math.max(1,Math.floor(num/40));
        const timer=setInterval(()=>{
            cur+=step; if(cur>=num){cur=num;clearInterval(timer);}
            el.textContent=cur+suffix;
        },30);
        counterObs.unobserve(el);
    });
},{threshold:0.5});
counters.forEach(c=>counterObs.observe(c));

/* ── Typing effect for hero ── */
const heroH1 = document.querySelector('.hero h1');
if(heroH1){
    const text=heroH1.textContent;
    heroH1.textContent='';
    heroH1.style.borderRight='2px solid rgba(255,255,255,.7)';
    let i=0;
    const type=()=>{
        if(i<text.length){heroH1.textContent+=text[i];i++;setTimeout(type,28);}
        else{setTimeout(()=>{heroH1.style.borderRight='none';},1200);}
    };
    setTimeout(type,400);
}

/* ── Parallax hero ── */
const hero = document.querySelector('.hero');
if(hero){
    window.addEventListener('scroll',()=>{
        const y=window.scrollY;
        if(y<window.innerHeight){
            hero.style.setProperty('--parallax', y*0.3+'px');
        }
    },{passive:true});
}

})();