module Jekyll
  class HideContent < Liquid::Block

    def render(context)
      super.gsub /.+/, ""
    end

  end
end

Liquid::Template.register_tag('hide', Jekyll::HideContent)
