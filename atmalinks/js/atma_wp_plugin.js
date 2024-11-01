function atmalinks_wrap_in_lazy_link() {
	try { 
		switchEditors.go('content', 'tinymce'); 
		tinyMCE.execCommand('mceReplaceContent', false, '<span class="atma-link">{$selection}</span>');
	} catch(err) {}
}

function atmalinks_remove_lazy_link() {
	var selection = "", tnode, par, combs, i, il, chs, node; 
	try {
		switchEditors.go('content', 'tinymce'); 
		if (tinyMCE && tinyMCE.activeEditor) {
			selection = tinyMCE.activeEditor.selection; 
		}
		if (selection) {
			node = selection.getNode()
			combs = [selection.getStart(), selection.getEnd(), node];
			if (node) {
				chs = node.children;
				for (i = 0, il = chs.length; i < il; i++) {
					combs.push(chs[i]);
				}
			}
			for (i = 0, il = combs.length; i < il; i++) {
				node = combs[i];
				if (node && node.className && node.className.search(/atma-link/i) != -1) {
					par = node.parentNode;
					tnode = node.firstChild; 
					par.replaceChild(tnode, node); 
					return; 
				}
			}
		}
	} catch (err) {}
}

function atmalinks_clear_all() {
	var content, doc, par, val, tnode, i, il, span, all_spans, expr  = /atma-link/i, n_matches = 0; 
	try {
		if (tinyMCE && tinyMCE.activeEditor && tinyMCE.activeEditor.dom && tinyMCE.activeEditor.dom.doc) {
			doc = tinyMCE.activeEditor.dom.doc 
			content = doc.body; 
			if (content) {
				all_spans = content.getElementsByTagName('span'); 
				for (i = 0, il = all_spans.length; i < il; i++) {
					span = all_spans[i]; 
					if (span && span.className && span.className.search(expr) != -1) {
						n_matches++; 
					}
				}
				for (i = 0; i < n_matches; i++) {
					all_spans = content.getElementsByTagName('span'); 
					for (j = 0, jl = all_spans.length; j < jl; j++) {
						span = all_spans[j];
						if (span && span.className && span.className.search(expr) != -1) {
							par = span.parentNode,
							tnode = span.firstChild
							par.replaceChild(tnode, span); 
							break; 
						}
					}
				}
			}
		}
	} catch(err) {}
}
