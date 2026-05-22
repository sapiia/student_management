import './bootstrap';

const parser = new DOMParser();

function isPlainLeftClick(event) {
    return event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;
}

function isPjaxLink(link) {
    if (!link || link.dataset.noPjax !== undefined) {
        return false;
    }

    if (link.target || link.hasAttribute('download')) {
        return false;
    }

    const url = new URL(link.href, window.location.href);

    return url.origin === window.location.origin
        && ['http:', 'https:'].includes(url.protocol)
        && url.href !== window.location.href;
}

function runBodyScripts(container) {
    container.querySelectorAll('script').forEach((script) => {
        const freshScript = document.createElement('script');

        Array.from(script.attributes).forEach((attribute) => {
            freshScript.setAttribute(attribute.name, attribute.value);
        });

        freshScript.textContent = script.src || script.type === 'module'
            ? script.textContent
            : `(() => {\n${script.textContent}\n})();`;

        script.replaceWith(freshScript);
    });
}

function swapPage(html, url, shouldPushState = true) {
    const nextDocument = parser.parseFromString(html, 'text/html');

    document.title = nextDocument.title;
    document.body.className = nextDocument.body.className;
    document.body.innerHTML = nextDocument.body.innerHTML;
    runBodyScripts(document.body);
    window.scrollTo(0, 0);

    if (shouldPushState) {
        window.history.pushState({ pjax: true }, '', url);
    }
}

async function visit(url, shouldPushState = true) {
    document.documentElement.classList.add('is-pjax-loading');

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                Accept: 'text/html, application/xhtml+xml',
            },
        });

        if (!response.ok) {
            window.location.href = url;
            return;
        }

        const html = await response.text();
        swapPage(html, response.url || url, shouldPushState);
    } catch {
        window.location.href = url;
    } finally {
        document.documentElement.classList.remove('is-pjax-loading');
    }
}

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const link = event.target.closest('a[href]');

    if (!isPlainLeftClick(event) || !isPjaxLink(link)) {
        return;
    }

    event.preventDefault();
    visit(link.href);
});

window.addEventListener('popstate', () => {
    visit(window.location.href, false);
});
